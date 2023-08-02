<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\CommentAlbum;
use App\Entity\Contact;
use App\Entity\Photos;
use App\Entity\User;
use App\Form\CommentAlbumType;
use App\Form\ContactFormType;
use App\Repository\AlbumRepository;
use App\Repository\CategoryRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Vich\UploaderBundle\Handler\DownloadHandler;
use ZipArchive;

class HomeController extends AbstractFrontController
{
    #[Route('/', name: 'home')]
    public function index(AlbumRepository $albumRepository): Response
    {
        $albums = $albumRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'albums' => $albums
        ]);
    }

    #[Route('/script-create-user', name: 'scriptCreateUser')]
    public function createUser(UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $user = new User();
        $user->setEmail('tristan.meillat28@gmail.com');
        $user->setRoles(['ROLE_USER']); // You can add more roles if needed
        $user->setPassword($userPasswordHasherInterface->hashPassword($user, 'devdev')); // Replace 'password123' with the desired password
        $user->setPseudo('Tristan');

        return $this->redirectToRoute('home');
    }

    #[Route('/photo/download/one/{id}', name: 'downloadOneImage')]
    public function downloadOneImage(Photos $photo, DownloadHandler $downloadHandler, EntityManagerInterface $em): Response
    {
        $photo->setNumberSave($photo->getNumberSave() + 1);
        $countSave = 0;
        $album =  $photo->getAlbum();
        $files = $album->getPhotos();
        foreach ($files as  $file) {
            $countSave += $file->getNumberSave();
        }
        $averageSave = $countSave / count($album->getPhotos());
        $album->setSave($averageSave);
        $em->persist($album);
        $em->flush();
        return $downloadHandler->downloadObject($photo, $fileField = 'imageFile');
    }


    #[Route('/photo/download/all/{id}', name: 'downloadAllImage')]
    public function downloadAll(Album $album, EntityManagerInterface $em) : Response
    {
        
        $files = $album->getPhotos();
        if (count($files) > 0) {
            $zip = new ZipArchive();
            $filesystem = new Filesystem();
            $tmp_file = $filesystem->tempnam('./tmp', '');
            $zip->open($tmp_file, ZipArchive::CREATE);
            $slugger = new AsciiSlugger();
            $zipName = $slugger->slug($album->getName()) . "_album.zip";
            if ($zip->open($tmp_file, ZipArchive::CREATE) == TRUE)
            {
                
                foreach ($files as  $file) {
                    $filePath = $this->getParameter('kernel.project_dir') . "/public/images/upload/photos/" . $file->getImageName();
                    $zip->addFile($filePath, $slugger->slug($album->getName()) . $file->getImageName());
                    $file->setNumberSave($file->getNumberSave() + 1);
                    $em->persist($file);
                }
                $zip->close();
                $em->flush();
            }

            $countSave = 0;
            foreach ($files as  $file) {
                $countSave += $file->getNumberSave();
            }
            $averageSave = $countSave / count($album->getPhotos());
            $album->setSave($averageSave);
            $em->flush();
        }
        


        $response = new Response(file_get_contents($tmp_file));
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $zipName . '"');
        $response->headers->set('Content-length', filesize($tmp_file));

        @unlink($tmp_file);

        return $response;
        // return $downloadHandler->downloadObject($file, $fileField = 'file');
    }

    #[Route('/albums/{valueSearch}', name: 'albums_all')]
    public function allAlbum(AlbumRepository $albumRepository, string $valueSearch = null): Response
    {
        
        $albums = $albumRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('home/albums.html.twig', [
            'controller_name' => 'HomeController',
            'albums' => $albums,
            'searchValue' => $valueSearch
        ]);
    }

    #[Route('/albums/detail/{id}', name: 'albums_detail')]
    public function detailAlbum(Request $request, Album $album, EntityManagerInterface $em): Response
    {
        $album->setView($album->getView() + 1);
        $em->persist($album);
        $em->flush();

        $comments = $album->getCommentAlbums();

        $commentNew = new CommentAlbum;
        $commentForm = $this->createForm(CommentAlbumType::class, $commentNew);

        $form_send = false;

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $commentNew->setCreatedAt(new DateTime('now'));
            $commentNew->setUpdatedAt(new DateTime('now'));
            $commentNew->setAlbum($album);
            $em->persist($commentNew);
            $em->flush();
            //TODO SEND EMAIL
            $form_send = true;
        }

        return $this->render('home/albumDetail.html.twig', [
            'controller_name' => 'HomeController',
            'album' => $album,
            'numberView' => $album->getView(),
            'formComment' => $commentForm,
            'formCommentSend' => $form_send,
            'comments' => $comments
        ]);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, EntityManagerInterface $em, Recaptcha3Validator $recaptcha3Validator): Response
    {
        $contact = new Contact;
        $contactForm = $this->createForm(ContactFormType::class, $contact);
        $form_send = false;


        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contact->setCreatedAt(new DateTime('now'));
            $contact->setUpdatedAt(new DateTime('now'));
            $em->persist($contact);
            $em->flush();
            //TODO SEND EMAIL
            $form_send = true;
        }

        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController',
            'form'=> $contactForm,
            'form_send' => $form_send
        ]);
    }

    #[Route('/mentions-legales', name: 'mentions-legales')]
    public function mentionsLegales(): Response
    {
        return $this->render('home/mentionsLegales.html.twig', [
            'controller_name' => 'HomeController'
        ]);
    }

    #[Route('/politique-confidentialite', name: 'politique-confidentialite')]
    public function politiqueConfidentialite(): Response
    {
        return $this->render('home/politiqueConfidentialite.html.twig', [
            'controller_name' => 'HomeController'
        ]);
    }
}
