<?php

// src/Controller/AdminController.php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;
use App\Entity\Company;
use App\Entity\EventSelection;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(EntityManagerInterface $entityManager)
    {
        // Fetch data using EntityManager
        $totalEvents = $entityManager->getRepository(EventSelection::class)->count(['isPaid' => true]);
        $availableEvents = $entityManager->getRepository(Event::class)->count(['status' => 'available']);
        $totalCompanies = $entityManager->getRepository(Company::class)->count([]);

        return $this->render('admin/index.html.twig', [
            'totalEvents' => $totalEvents,
            'availableEvents' => $availableEvents,
            'totalCompanies' => $totalCompanies,
        ]);
    }

    #[Route('/admin/events', name: 'admin_events')]
    public function events(EntityManagerInterface $entityManager)
    {
        // Fetch all paid events from EventSelection
        $paidEvents = $entityManager->getRepository(EventSelection::class)->findBy(['isPaid' => true]);

        return $this->render('admin/events.html.twig', [
            'paidEvents' => $paidEvents,
        ]);
    }

    #[Route('/admin/companies', name: 'admin_companies')]
    public function companies(EntityManagerInterface $entityManager)
    {
        // Ensure categories are capitalized and consistent
        $categories = ['Food', 'Decor', 'Photography', 'Venues', 'Music'];  // Update to Capitalize
        $companiesByCategory = [];

        foreach ($categories as $category) {
            // Make sure category matches database case
            $companiesByCategory[strtolower($category)] = $entityManager->getRepository(Company::class)->findBy(['category' => strtolower($category)]);
        }

        return $this->render('admin/companies.html.twig', [
            'companiesByCategory' => $companiesByCategory,
        ]);
    }
    #[Route('/admin/companies/{category}', name: 'admin_companies_by_category')]
    public function companiesByCategory(EntityManagerInterface $entityManager, string $category)
    {
        // Fetch companies by selected category
        $companies = $entityManager->getRepository(Company::class)->findBy(['category' => ucfirst($category)]);
    
        return $this->render('admin/companies.html.twig', [
            'category' => $category,
            'companies' => $companies,  // Ensure this variable is passed
        ]);
    }
    #[Route('/admin/event-statistics', name: 'admin_event_statistics')]
    public function eventStatistics(EntityManagerInterface $entityManager)
    {
        // Fetch all paid events from EventSelection
        $paidEvents = $entityManager->getRepository(EventSelection::class)->findBy(['isPaid' => true]);
    
        // Initialize statistics arrays
        $eventTypes = [];
        $eventDates = [];
        $eventTimes = [];
        $totalRevenue = 20000; // If there is a price field in EventSelection
    
        foreach ($paidEvents as $event) {
            // Count event types
            $eventTypes[$event->getEventType()] = ($eventTypes[$event->getEventType()] ?? 0) + 1;
    
            // Count event dates
            $eventDate = $event->getEventDate()->format('Y-m-d');
            $eventDates[$eventDate] = ($eventDates[$eventDate] ?? 0) + 1;
    
            // Count event times
            $eventTimes[$event->getEventTime()] = ($eventTimes[$event->getEventTime()] ?? 0) + 1;
    
            // Calculate revenue if applicable
            if (method_exists($event, 'getAmountPaid')) {
                $totalRevenue += $event->getAmountPaid();
            }
        }
    
        return $this->render('admin/event_statistics.html.twig', [
            'eventTypes' => $eventTypes,
            'eventDates' => $eventDates,
            'eventTimes' => $eventTimes,
            'totalRevenue' => $totalRevenue, // If revenue tracking exists
        ]);
    }
    
}

