<?php

namespace App\Controller;

use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFrontController extends AbstractController
{
  protected $request;

  public function __construct(RequestStack $request)
  {
    $this->request = $request;
  }

  protected function render(string $view, array $parameters = [], Response $response = null): Response
  {
    $searchForm = $this->createForm(SearchType::class);
    $searchForm->handleRequest($this->request->getCurrentRequest());
    if ($searchForm->isSubmitted() && $searchForm->isValid()) {
      
      $valueSearch = $this->request->getCurrentRequest()->get('search')['search'];
      $routeName = $this->request->getCurrentRequest()->attributes->get('_route');
      if ($routeName != "albums_all") {
        return new RedirectResponse("/albums/" . $valueSearch);
        die;
      }
    }

    $parameters['form_search'] = $searchForm;

    return parent::render($view, $parameters, $response);
  }
}
