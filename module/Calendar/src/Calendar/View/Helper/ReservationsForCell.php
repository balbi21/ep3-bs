<?php

namespace Calendar\View\Helper;

use Square\Entity\Square;
use Zend\View\Helper\AbstractHelper;

class ReservationsForCell extends AbstractHelper
{

    public function __invoke(array $reservationsForCol, Square $square)
    {
        $reservationsForCell = array();

        foreach ($reservationsForCol as $rid => $reservation) {
            $booking = $reservation->needExtra('booking');

            $sid = $booking->get('sid');
            if ($sid == $square->need('sid')||is_null($sid)) {
                if ($booking->need('status') != 'cancelled') {
                    if ($booking->need('visibility') == 'public') {
                        $reservationsForCell[$rid] = $reservation;
                    }
                }
            }
        }

        return $reservationsForCell;
    }

}
