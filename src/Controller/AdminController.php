<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\Photos;
use App\Entity\User;
use App\Form\AlbumSelectionBanniereType;
use App\Form\AlbumType;
use App\Form\UserType;
use App\Repository\AlbumRepository;
use App\Repository\CategoryRepository;
use App\Repository\ContactRepository;
use App\Repository\PhotosRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/user/create', name: 'user_create')]
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $user->getPassword();
            // hash the password (based on the security.yaml config for the $user class)
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $plainPassword
            );
            $user->setPassword($hashedPassword);
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($user);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

        }

        return $this->render('admin/users/createUser.html.twig', [
            'controller_name' => 'AdminController',
            'form' => $form
        ]);
    }

    #[Route('/albums', name: 'albums')]
    public function Album(AlbumRepository $albumRepository) {
        $albums = $albumRepository->findBy(array(), array('updatedAt' => 'DESC'));
        return $this->render('admin/album/show.html.twig', [
            'controller_name' => 'AdminController',
            'albums' => $albums
        ]);
    }

    #[Route('/albums/selected', name: 'albums_selected')]
    public function AlbumSelected(AlbumRepository $albumRepository, Request $request, EntityManagerInterface $em) {
        $albums = $albumRepository->findBy(array(), array('banniere' => 'DESC'));
        $form = $this->createForm(AlbumSelectionBanniereType::class, ['selectedAlbums' => $albums]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $selectedAlbums = $form->getData()['selectedAlbums'];
            foreach ($albums as $album) {
                if (in_array($album, $selectedAlbums)) {
                    $album->setBanniere(true);
                } else {
                    $album->setBanniere(false);
                }
                $em->persist($album);
            }
            $em->flush();
            return $this->redirectToRoute("admin_albums_selected");

        }
        return $this->render('admin/album/selectedAlbum.html.twig', [
            'controller_name' => 'AdminController',
            'albums' => $albums,
            'form' => $form
        ]);
    }

    #[Route('/albums/create', name: 'albums_create')]
    public function CreateAlbum(Request $request, EntityManagerInterface $em, CategoryRepository $categoryRepository): Response
    {

        $album = new Album;
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($form['categories']->getData() != "") {
                $categories = explode(" ", $form['categories']->getData());
            } else {
                $categories = [];
            }
            foreach ($categories as $category) {
                $cateBdd = $categoryRepository->findOneBy(['name' => $category]);
                if (!$cateBdd) {
                    $newCate = new Category();
                    $newCate->setName($category);
                    $album->addCategory($newCate);
                } else {
                    $album->addCategory($cateBdd);
                }
            }
            $album->updateTimestamps();
            $album->setSave(0);
            $em->persist($album);
            $em->flush();

            $this->addFlash('success','l\'album a été créé avec succès !');

            return $this->redirectToRoute('admin_albums_modify', [
                'id' => $album->getId()
            ]);
        }

        return $this->render('admin/album/create.html.twig', [
            'controller_name' => 'AdminController',
            'form' => $form
        ]);
    }

    #[Route('/albums/modify/{id}', name: 'albums_modify')]
    public function modifyAlbum(Request $request, EntityManagerInterface $em, Album $album, CategoryRepository $categoryRepository): Response
    {
        
        if ($album != null) {
            $form = $this->createForm(AlbumType::class, $album);
            $categoriesBdd = $album->getCategories();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form['categories']->getData() != "") {
                    $categories = explode(" ", $form['categories']->getData());
                } else {
                    $categories = [];
                }
                foreach ($categoriesBdd as $key => $categoryBdd) {
                    $category = in_array($categoryBdd->getName(), $categories);
                    if ($category === false) {
                        $album->removeCategory($categoryBdd);
                    }
                }
                foreach ($categories as $category) {
                    $cateBdd = $categoryRepository->findOneBy(['name' => $category]);
                    if (!$cateBdd) {
                        $newCate = new Category();
                        $newCate->setName($category);
                        $album->addCategory($newCate);
                    } else {
                        $album->addCategory($cateBdd);
                    }
                }
                $album->updateTimestamps();
                $em->persist($album);
                $em->flush();

                $this->addFlash('success','l\'album a été modifié avec succès !');
            }
        } else {
            $this->addFlash('danger','Aucun album trouvé ...');
        }

        return $this->render('admin/album/create.html.twig', [
            'controller_name' => 'AdminController',
            'album' => $album,
            'form' => $form,
            'categories' => $categoriesBdd
        ]);
    }

    #[Route('/album/delete/{id}', name: 'albums_delete')]
    public function deleteAlbum(Album $album, EntityManagerInterface $em) {
        if (!empty($album)) {
            $em->remove($album);
            $em->flush();
        }

        return $this->redirectToRoute('admin_albums');
    }

    #[Route('/messages', name: 'messages_list')]
    public function messageList(ContactRepository $contactRepository) {
        $messages = $contactRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('admin/message/list.html.twig', [
            'controller_name' => 'AdminController',
            'messages' => $messages
        ]);
    }

    #[Route('/messages/{id}', name: 'messages_show')]
    public function messageDetail(Contact $contact) {
        return $this->render('admin/message/show.html.twig', [
            'controller_name' => 'AdminController',
            'message' => $contact
        ]);
    }

}
