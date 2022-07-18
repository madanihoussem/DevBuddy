<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Entity\Newsletter;
use App\Entity\User;
use App\Repository\ContactRepository;
use App\Repository\NewsletterRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    public function __construct(UserRepository $userRepository, ContactRepository $contactRepository, NewsletterRepository $newsletterRepository)
    {
        $this->userRepository = $userRepository;
        $this->contactRepository = $contactRepository;
        $this->newsletterRepository = $newsletterRepository;

    }
    #[Route('/admin1', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('DevBuddy');
    }

    public function configureMenuItems(): iterable
    {
        
        yield MenuItem::linkToRoute('WebSite', 'fa fa-home', 'app_home');
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class)->setBadge(count($this->userRepository->findAll()));
        yield MenuItem::linkToCrud('Contacts', 'fas fa-message', Contact::class)->setBadge(count($this->contactRepository->findAll()));
        yield MenuItem::linkToCrud('Newsletters', 'fa-solid fa-at', Newsletter::class)->setBadge(count($this->newsletterRepository->findAll()));
    }
}
