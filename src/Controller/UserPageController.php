<?php

namespace App\Controller;

use App\Entity\EventSelection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserPageController extends AbstractController
{
    #[Route('/user', name: 'user_home')]
    public function index(): Response
    {
        $eventOptions = ['Birthday Party', 'Gender Reveal', 'Financial Event', 'Wedding', 'Anniversary', 'Corporate Event'];

        return $this->render('user_page/index.html.twig', [
            'greeting' => 'Hey, welcome! Let your experience begin!',
            'eventOptions' => $eventOptions,
        ]);
    }

    #[Route('/user/event-selection', name: 'user_event_selection', methods: ['POST'])]
    public function eventSelection(Request $request): Response
    {
        $selectedEvent = $request->request->get('eventType');

        if (!$selectedEvent) {
            $this->addFlash('error', 'Please select an event type.');
            return $this->redirectToRoute('user_home');
        }

        $availableTimes = ['10:00 AM', '2:00 PM', '6:00 PM', '8:00 PM'];

        return $this->render('user_page/choose_time.html.twig', [
            'event' => $selectedEvent,
            'availableTimes' => $availableTimes,
        ]);
    }

    #[Route('/user/event-details', name: 'user_event_details', methods: ['POST'])]
    public function eventDetails(Request $request, EntityManagerInterface $entityManager): Response
    {
        $eventType = $request->request->get('eventType');
        $eventTime = $request->request->get('eventTime');
        $eventDate = $request->request->get('eventDate');

        if (!$eventType || !$eventTime || !$eventDate) {
            $this->addFlash('error', 'All fields are required.');
            return $this->redirectToRoute('user_event_selection');
        }

        $eventSelection = new EventSelection();
        $eventSelection->setEventType($eventType);
        $eventSelection->setEventTime($eventTime);
        $eventSelection->setEventDate(new \DateTime($eventDate));

        $entityManager->persist($eventSelection);
        $entityManager->flush();

        return $this->redirectToRoute('user_dashboard', ['eventId' => $eventSelection->getId()]);
    }

    #[Route('/user/dashboard/{eventId}', name: 'user_dashboard', methods: ['GET'])]
    public function dashboard(int $eventId, EntityManagerInterface $entityManager): Response
    {
        $eventSelection = $entityManager->getRepository(EventSelection::class)->find($eventId);

        if (!$eventSelection) {
            $this->addFlash('error', 'Event not found.');
            return $this->redirectToRoute('user_home');
        }

        $categories = ['Food', 'Decor', 'Music', 'Photography', 'Venues'];

        return $this->render('user_page/dashboard.html.twig', [
            'eventSelection' => $eventSelection,
            'categories' => $categories,
        ]);
    }

    #[Route('/user/select-category/{eventId}/{category}', name: 'user_select_category', methods: ['GET', 'POST'])]
    public function selectCategory(int $eventId, string $category, Request $request, EntityManagerInterface $entityManager): Response
    {
        $eventSelection = $entityManager->getRepository(EventSelection::class)->find($eventId);
    
        if (!$eventSelection) {
            $this->addFlash('error', 'Event not found.');
            return $this->redirectToRoute('user_home');
        }
    
        if ($request->isMethod('POST')) {
            $selectedOption = $request->request->get('selection');
            $extraSelection = $request->request->all('extras'); // Additional options based on category
            $comment = $request->request->get('comment'); // Get comment for the category
    
            // Initialize category comments if not set
            $categoryComments = $eventSelection->getCategoryComments() ?? [];
    
            // Update the comment for the selected category
            $categoryComments[$category] = $comment;
            $eventSelection->setCategoryComments($categoryComments);
    
            // Calculate the total price
            $totalPrice = 0;
    
            switch ($category) {
                case 'Food':
                    $eventSelection->setFoodType($selectedOption);
                    $eventSelection->setSelectedRestaurant($request->request->get('restaurant'));
    
                    $menuSelection = [];
                    foreach ($request->request->all('menu') as $item => $quantity) {
                        if (intval($quantity) > 0) {
                            $menuSelection[$item] = intval($quantity);
                            // Calculate the total price for food
                            $totalPrice += $this->getFamousRestaurants()[$eventSelection->getSelectedRestaurant()][$item] * $quantity;
                        }
                    }
                    $eventSelection->setMenuSelection($menuSelection);
                    break;
    
                case 'Decor':
                    $eventSelection->setDecorType($selectedOption);
                    $eventSelection->setDecorExtras($extraSelection);
                    // Calculate the total price for decor extras
                    foreach ($extraSelection as $extra) {
                        $totalPrice += $this->getCategoryExtras($category)[$extra] ?? 0;
                    }
                    break;
    
                case 'Music':
                    $eventSelection->setMusicType($selectedOption);
                    $eventSelection->setMusicExtras($extraSelection);
                    // Calculate the total price for music extras
                    foreach ($extraSelection as $extra) {
                        $totalPrice += $this->getCategoryExtras($category)[$extra] ?? 0;
                    }
                    break;
    
                case 'Photography':
                    $eventSelection->setPhotographyType($selectedOption);
                    $eventSelection->setPhotographyExtras($extraSelection);
                    // Calculate the total price for photography extras
                    foreach ($extraSelection as $extra) {
                        $totalPrice += $this->getCategoryExtras($category)[$extra] ?? 0;
                    }
                    break;
    
                case 'Venues':
                    $eventSelection->setVenueType($selectedOption);
                    $eventSelection->setVenueExtras($extraSelection);
                    // Calculate the total price for venue extras
                    foreach ($extraSelection as $extra) {
                        $totalPrice += $this->getCategoryExtras($category)[$extra] ?? 0;
                    }
                    break;
            }
    
            // Update the total price
            $eventSelection->setTotalPrice($eventSelection->getTotalPrice() + $totalPrice);
    
            $entityManager->persist($eventSelection);
            $entityManager->flush();
    
            $this->addFlash('success', ucfirst($category) . ' selection saved.');
            return $this->redirectToRoute('user_dashboard', ['eventId' => $eventId]);
        }
    
        $options = $this->getCategoryOptions($category);
        $extras = $this->getCategoryExtras($category);
        $restaurants = $category === 'Food' ? $this->getFamousRestaurants() : [];
    
        return $this->render('user_page/select_category.html.twig', [
            'eventSelection' => $eventSelection,
            'category' => $category,
            'options' => $options,
            'extras' => $extras,
            'restaurants' => $restaurants,
            'categoryComments' => $eventSelection->getCategoryComments(),
        ]);
    }

    private function getCategoryOptions(string $category): array
    {
        return [
            'Food' => ['Buffet', 'Plated Dinner', 'Finger Foods'],
            'Decor' => ['Classic', 'Modern', 'Vintage'],
            'Music' => ['DJ', 'Live Band', 'Acoustic'],
            'Photography' => ['Traditional', 'Candid', 'Drone Photography'],
            'Venues' => ['Indoor', 'Outdoor', 'Beachfront'],
        ][$category] ?? [];
    }

    private function getCategoryExtras(string $category): array
    {
        return [
            'Decor' => [
                'Floral Arrangements' => 50,
                'Lighting Effects' => 30,
                'Custom Backdrops' => 80,
            ],
            'Music' => [
                'Karaoke Setup' => 40,
                'Live Instrumentals' => 100,
                'Custom Playlists' => 20,
            ],
            'Photography' => [
                'Photo Booth' => 150,
                'Video Coverage' => 200,
                'Drone Footage' => 250,
            ],
            'Venues' => [
                'VIP Lounge' => 300,
                'Outdoor Seating' => 150,
                'Custom Stage Design' => 500,
            ],
        ][$category] ?? [];
    }

    private function getFamousRestaurants(): array
    {
        return [
            'La Belle Cuisine' => [
                'Steak' => 25,
                'Pasta Carbonara' => 18,
                'Grilled Salmon' => 22,
            ],
            'Gourmet Palace' => [
                'Sushi Platter' => 30,
                'Teriyaki Chicken' => 20,
                'Miso Soup' => 5,
            ],
            'Royal Feast' => [
                'Lamb Chops' => 28,
                'Truffle Risotto' => 24,
                'Caesar Salad' => 12,
            ],
        ];
    }
    #[Route('/user/payment/process/{eventId}', name: 'user_process_payment', methods: ['POST'])]
    public function payment(int $eventId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $eventSelection = $entityManager->getRepository(EventSelection::class)->find($eventId);
    
        if (!$eventSelection) {
            $this->addFlash('error', 'Événement non trouvé.');
            return $this->redirectToRoute('user_home');
        }
    
        // Logique de traitement du paiement
        // Par exemple, enregistrement de l'état de paiement dans la base de données
        $eventSelection->setIsPaid(true);
        $entityManager->flush();
    
        $this->addFlash('success', 'Paiement effectué avec succès.');
        return $this->redirectToRoute('user_dashboard', ['eventId' => $eventId]);
    }
}
