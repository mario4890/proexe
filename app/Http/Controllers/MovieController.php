<?php

namespace App\Http\Controllers;

use External\Foo\Movies\MovieService as MovieServiceFoo;
use External\Bar\Movies\MovieService as MovieServiceBar;
use External\Baz\Movies\MovieService as MovieServiceBaz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getTitles(Request $request): JsonResponse
    {
        $NUM_OF_ATTEMPTS = 10;
        $attempts = 0;

        do {
            try
            {
                $data = $this->executeCode();
            } catch (\Exception $e) {
                $attempts++;
                sleep(1);
                continue;
            }

            break;

        } while($attempts < $NUM_OF_ATTEMPTS);

        return response()->json($data);
    }

    private function executeCode(){
        $movieServiceFooObj = new MovieServiceFoo();
        $randFooTitle       = rand(0, 2);
        $movieServiceFoo    = $movieServiceFooObj->getTitles();
        $movieTitleFoo      = $movieServiceFoo[$randFooTitle];
        $movieServiceBarObj = new MovieServiceBar();
        $randBarTitle       = rand(0, 1);
        $movieServiceBar    = $movieServiceBarObj->getTitles();
        $movieTitleBar      = $movieServiceBar['titles'][$randBarTitle]['title'];
        $movieServiceBazObj = new MovieServiceBaz();
        $randBazTitle       = rand(0, 2);
        $movieServiceBaz    = $movieServiceBazObj->getTitles();
        $movieTitleBaz      = $movieServiceBaz['titles'][$randBazTitle];

        $data = [
            $movieTitleFoo,
            $movieTitleBar,
            $movieTitleBaz
        ];

        return $data;
    }
}
