<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\MessageRepositoryInterface;

/**
 * @OA\Info(title="Auto SMS API", version="1.0")
 */
class MessageController extends Controller
{
    protected MessageRepositoryInterface $repo;

    public function __construct(MessageRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @OA\Get(
     *     path="/api/messages/sent",
     *     summary="Gönderilen mesajları listeler",
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         description="Filtre: telefon numarası",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="segment",
     *         in="query",
     *         description="Filtre: segment",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Başarılı",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="segment", type="string"),
     *                     @OA\Property(property="phone", type="string"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="content", type="string"),
     *                     @OA\Property(property="status", type="string"),
     *                     @OA\Property(property="external_message_id", type="string"),
     *                     @OA\Property(property="sent_at", type="string", format="date-time"),
     *                     @OA\Property(property="response_payload", type="object")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function listSent(Request $request)
    {
        $filters = $request->only(['phone', 'segment']);
        $messages = $this->repo->listSent($filters);

        return response()->json([
            'success' => true,
            'data' => $messages,
        ]);
    }
}
