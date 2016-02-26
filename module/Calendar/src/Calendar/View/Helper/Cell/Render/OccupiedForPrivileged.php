<?php

namespace Calendar\View\Helper\Cell\Render;

use Booking\Service\BookingStatusService;
use Zend\View\Helper\AbstractHelper;

class OccupiedForPrivileged extends AbstractHelper
{

    protected $bookingStatusService;

    public function __construct(BookingStatusService $bookingStatusService)
    {
        $this->bookingStatusService = $bookingStatusService;
    }

    public function __invoke(array $reservations, array $cellLinkParams)
    {
        $view = $this->getView();

        $reservationsCount = count($reservations);

        if ($reservationsCount > 1) {
            return $view->calendarCellLink($this->view->t('Occupied'), $view->url('backend/booking/edit', [], $cellLinkParams), 'cc-singleA');
        } else {
            $reservation = current($reservations);
            $booking = $reservation->needExtra('booking');
            $bookingStatusColor = $this->bookingStatusService->getStatusColor($booking->getBillingStatus());

            if ($bookingStatusColor) {
                $cellStyle = 'outline: solid 3px ' . $bookingStatusColor;
            } else {
                $cellStyle = null;
            }

            $fitnessstate = $booking->needExtra('user')->getState();
            $cellLabel = $booking->needExtra('user')->need('alias');
            
            $cellGroup = ' cc-group-' . $booking->need('bid');

            switch ($booking->need('status')) {
                case 'single':
                    switch($fitnessstate){
                        case 'trainer':
                            return $view->calendarCellLink($cellLabel, $view->url('backend/booking/edit', [], $cellLinkParams), 'cc-single' . $cellGroup, null, $cellStyle);
                        case 'poweruser':
                            return $view->calendarCellLink($cellLabel, $view->url('backend/booking/edit', [], $cellLinkParams), 'cc-singleA' . $cellGroup, null, $cellStyle);
                        case 'user':
                            return $view->calendarCellLink($cellLabel, $view->url('backend/booking/edit', [], $cellLinkParams), 'cc-singleB' . $cellGroup, null, $cellStyle);
                        case 'beginner':
                            return $view->calendarCellLink($cellLabel, $view->url('backend/booking/edit', [], $cellLinkParams), 'cc-singleC' . $cellGroup, null, $cellStyle);
                        default:
                            return $view->calendarCellLink($cellLabel, $view->url('backend/booking/edit', [], $cellLinkParams), 'cc-single' . $cellGroup, null, $cellStyle);
                    }
                case 'subscription':
                    return $view->calendarCellLink($cellLabel, $view->url('backend/booking/edit', [], $cellLinkParams), 'cc-multiple' . $cellGroup, null, $cellStyle);
            }
        }
    }

}
