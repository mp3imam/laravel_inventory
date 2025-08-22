<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SatkerModel;
class DocsAPIController extends Controller
{
    //
    /**
     * @OA\Get(
     *    path="/api/v1/satker",
     *    operationId="index",
     *    tags={"satker"},
     *    summary="Get list of satker",
     *    description="Get list of satker",
     *    @OA\Parameter(name="limit", in="query", description="limit", required=false,
     *        @OA\Schema(type="integer")
     *    ),
     *    @OA\Parameter(name="page", in="query", description="the page number", required=false,
     *        @OA\Schema(type="integer")
     *    ),
     *    @OA\Parameter(name="order", in="query", description="order  accepts 'asc' or 'desc'", required=false,
     *        @OA\Schema(type="string")
     *    ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="array",
     *              @OA\Items(ref="#/components/schemas/SatkerModel"))
     *          )
     *       ),
     *     @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example="500"),
     *             @OA\Property(property="message", example="Internal Server Error")
     *          )
     *      ),
     *
     *  )
     */

    public function index(Request $request)
    {
        try {
            $limit = $request->limit ?: 15;
            $order = $request->order == 'asc' ? 'asc' : 'desc';

            $satker = SatkerModel::orderBy('updated_at', $order)
            ->select('id', 'kode_satker', 'name', 'address')
            ->paginate($limit);;

            return response()->json(['status' => 200, 'data' => $satker]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'message' => $e->getMessage()]);
        }
    }
}
