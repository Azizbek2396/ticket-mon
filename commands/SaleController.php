<?php

namespace app\commands;

use app\models\Events;
use app\models\Saver;
use GuzzleHttp\Client;
use yii\console\Controller;

class SaleController extends Controller
{
    public function actionAuth()
    {
        $client = new Client();
        $url = 'https://cabinet.cultureticket.uz/api/CultureTicket/Token';

        $res = $client->request('POST', $url, [
            'json' => [
                "login" => 'umar@iticket.uz',
                "password" => '123456'
            ],
            'verify' => false
        ]);

        $json = json_decode($res->getBody()->getContents(), true);
        $path = dirname(__DIR__, 1) . '/web/data/auth';

        if (isset($json['result']['accessToken'])){
            file_put_contents($path, $json['result']['accessToken']);
        }
        var_dump($json);
    }

    public function getToken()
    {
        $token = file_get_contents(dirname(__DIR__, 1) . '/web/data/auth');
        return $token;
    }

    public function getResponse($url) {
        $client = new Client();
        $res = $client->request('GET', $url, [
            'headers' => [
                'Authorization' => "Bearer " . $this->getToken(),
            ],
            'verify' => false
        ]);

        return $res;
    }

    public function actionSold()
    {
        $events = Events::find()->all();

        foreach ($events as $event) {
            if ($event->session_id && ($event->is_active === 1)) {
                $url1 ='https://cabinet.cultureticket.uz/api/CultureTicket/SessionTickets/' . $event->session_id;
                $res = $this->getResponse($url1);
                $tickets = json_decode($res->getBody()->getContents(), true);

                $url2 = 'https://cabinet.cultureticket.uz/api/CultureTicket/PalaceHallSeats/' . $event->hall;
                $res2 = $this->getResponse($url2);
                $seats = json_decode($res2->getBody()->getContents(), true);

                $soldTickets = [];
                $rejectedTickets = [];
                foreach ($tickets['result'] as $ticket) {
                    if(($ticket['ticketStatusName'] === "Проданный") && ($ticket['tarifName'] !== "Пригласительное место")) {
                        array_push($soldTickets, $ticket);
                    }
                    if($ticket['ticketStatusName'] === "Возвратный") {
                        array_push($rejectedTickets, $ticket);
                    }
                }

                $soldSeats = [];
                $rejectedSeats = [];
                foreach ($soldTickets as $ticket) {
                    foreach ($seats['result'] as $seat) {
                        if(($seat['sectorName'] === $ticket['sectorName']) && ($seat['seatNumber'] === (int)$ticket['seatNumber']) && ($seat['rowNumber'] === (int)$ticket['rowNumber'])) {
                            array_push($soldSeats, $seat);
                        }
                    }
                }
                foreach ($rejectedTickets as $ticket) {
                    foreach ($seats['result'] as $seat) {
                        if(($seat['sectorName'] === $ticket['sectorName']) && ($seat['seatNumber'] === (int)$ticket['seatNumber']) && ($seat['rowNumber'] === (int)$ticket['rowNumber'])) {
                            array_push($rejectedSeats, $seat);
                        }
                    }
                }

                if (!empty($soldSeats)){
                    foreach ($soldSeats as $soldSeat) {
                        $model = new Saver();
                        $model->event_id = $event->id;
                        $model->seat_id = 'seat-' . $soldSeat['svgSeatId'];
                        $model->place_title = 'Sector: ' . $soldSeat['sectorName'] . ' Row: ' . $soldSeat['rowNumber'] . ' Seat: ' . $soldSeat['seatNumber'];
                        $model->comment = 'Проданное место';
                        $model->color = 'CCCCCC';
                        if(Saver::find()->where(['event_id'=>$event->id,'seat_id'=>$model->seat_id])->one()){
                            $a = 1;
                        } else{
                            $model->save();
                        }
                    }
                }
                if (!empty($rejectedSeats)) {
                    foreach ($rejectedSeats as $rejectedSeat) {
                        if (Saver::find()->where(['event_id' => $event->id, 'seat_id' => 'seat-' . $rejectedSeat['svgSeatId']])->one()){
                            Saver::find()->where(['event_id' => $event->id, 'seat_id' => 'seat-' . $rejectedSeat['svgSeatId']])->one()->delete();
                        }
                    }
                }
            }
        }
        return "Sold tickets are recalculated";
    }
}