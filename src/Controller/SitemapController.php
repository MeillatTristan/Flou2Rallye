<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapController extends AbstractController
{

  private $albumRepository;

  public function __construct(AlbumRepository $albumRepository)
  {
    $this->albumRepository = $albumRepository;
  }

  #[Route('/sitemap.xml', name: 'sitemap')]
  public function index(Request $request)
  {

    $hostname = $request->getSchemeAndHttpHost();

    $urls = [];

    $urls[] = ['loc' => $this->generateUrl('home')];
    $urls[] = ['loc' => $this->generateUrl('albums_all')];
    $urls[] = ['loc' => $this->generateUrl('contact')];
    $urls[] = ['loc' => $this->generateUrl('mentions-legales')];
    $urls[] = ['loc' => $this->generateUrl('politique-confidentialite')];

    $albums = $this->albumRepository->findAll();
    foreach ($albums as $album) {

      $urls[] = [
        'loc' => $this->generateUrl(
          'albums_detail',
          ['id' => $album->getId()],
          UrlGeneratorInterface::ABSOLUTE_URL
        ),
        'lastmod' => $album->getUpdatedAt()->format('Y-m-d'),
      ];
    }


    $response = new Response(
      $this->renderView('./sitemap/sitemap.html.twig', ['urls' => $urls, 'hostname' => $hostname]),
      200
    );
    $response->headers->set('Content-Type', 'text/xml');

    return $response;
  }
}
