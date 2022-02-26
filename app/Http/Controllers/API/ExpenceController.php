<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\API\Expence\StoreRequest;
use App\Models\Expence;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ExpenceController extends Controller
{
    public function index(Request $request)
    {
        $expenceToTake = Expence::where('expences.user_id', auth()->id())
        ->whereNotIn("expence_user.user_id", [auth()->id()])
        ->join('expence_user', 'expences.id', '=', 'expence_user.expence_id')
        ->selectRaw("expence_user.user_id as to_user_id, sum(expence_user.each_amount) as each_amount")
        ->groupBy('expence_user.user_id')
        ->get();

        $expenceToGive = Expence::where('expences.user_id', "!=", auth()->id())
        ->where("expence_user.user_id", auth()->id())
        ->join('expence_user', 'expences.id', '=', 'expence_user.expence_id')
        ->selectRaw("expences.user_id, expence_user.user_id as to_user_id, sum(expence_user.each_amount) as each_amount")
        ->groupBy('expences.user_id', 'expence_user.user_id')
        ->get();

        $data = [];
        $user_ids = [];

        foreach ($expenceToTake as $key => $take) {

            $give = $expenceToGive->where("user_id", $take->to_user_id)->first();

            $arr = [];

            $arr['user_id'] = $take->to_user_id;

            if ($give) {
                $arr["amount"] = (float) $take->each_amount - $give->each_amount;

                $user_ids[] = $give->user_id;
            } else {
                $arr["amount"] = (float) $take->each_amount;
            }

            $data[] = $arr;

        }

        foreach ($expenceToGive->whereNotIn('user_id', $user_ids) as $key => $give) {

            $arr = [];

            $arr['user_id'] = $give->user_id;

            $arr["amount"] = - (float) $give->each_amount;

            $data[] = $arr;

        }

        return response()->json([
            "messsage" => "Expence report!",
            "data" => $data
        ]);
    }

    public function store(StoreRequest $request)
    {
        $authId = auth()->id();

        $expenceUser = [];

        if ($request->type !== 2) {

            $amount = $this->roundOfDecimal($request->amount);

        } else {

            $amount = 0;

            foreach ($request->users as $key => $user) {
                $amount += $user['amount'];
            }

            $amount = $this->roundOfDecimal($amount);

        }

        // Store expence
        $expence = Expence::create([
            "amount" => $amount,
            "type" => $request->type,
            "user_id" => $authId,
        ]);

        // For equal
        if ($request->type === 1) {

            if ($request->user_ids) {

                $userIds = $request->user_ids;

                // Set login user to first
                array_unshift($userIds, $authId);

                $totalUsers = count( $userIds );

                $eachAmount = $this->roundOfDecimal( $amount / $totalUsers );

                $chekedAmount = $this->roundOfDecimal($eachAmount * $totalUsers);

                if ( $amount !== $chekedAmount ) {

                    $diffValue = $this->roundOfDecimal($amount - $chekedAmount);

                    $firstUserAmt = $this->roundOfDecimal($eachAmount + $diffValue);

                }

                foreach ($userIds as $key => $userId) {

                    if ($key === 0 && isset($firstUserAmt)) {

                        $expenceUser[$key]["each_amount"] = $firstUserAmt;

                    } else {

                        $expenceUser[$key]["each_amount"] = $eachAmount;

                    }

                    $expenceUser[$key]["expence_id"] = $expence->id;
                    $expenceUser[$key]["user_id"] = $userId;

                }

            }

        }

        // For exact
        if ($request->type === 2) {

            foreach ($request->users as $key => $user) {

                $expenceUser[$key]["each_amount"] = $user['amount'];
                $expenceUser[$key]["expence_id"] = $expence->id;
                $expenceUser[$key]["user_id"] = $user['id'];

            }

        }

        // For percent
        if ($request->type === 3) {

            foreach ($request->users as $key => $user) {

                $expenceUser[$key]["each_amount"] = ($user['percent'] / 100) * $amount;
                $expenceUser[$key]["expence_id"] = $expence->id;
                $expenceUser[$key]["percent"] = $user['percent'];
                $expenceUser[$key]["user_id"] = $user['id'];

            }

        }

        DB::table('expence_user')->insert($expenceUser);

        return response()->json([
            "messsage" => "Expence added successfully!"
        ]);
    }

    private function roundOfDecimal($value)
    {
        return (float) number_format((float)$value, 2, '.', '');
    }
}
