<?php

namespace App\Http\Controllers;

use \Exception;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests\HistoryCategory\HistoryCategoryRegisterRequest;
use App\Services\History\HistoryCategoryService;

class HistoryCategoryController extends Controller
{
    private $historyCategoryService;
    public function __construct(
        HistoryCategoryService $historyCategoryService,
    ) {
        $this->historyCategoryService = $historyCategoryService;
    }

    // カテゴリーの登録
    public function registerCategory(HistoryCategoryRegisterRequest $request)
    {
        try {
            $this->historyCategoryService->registerCategory(
                $request->userAccountId,
                $request->categories,
                $request->deleteCategoryIds,
            );
            return response()->json(
                null,
                Response::HTTP_OK
            );
        } catch (Exception $ex) {
            return response()->json(
                [
                    'message' =>  $ex->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    // 登録カテゴリーの取得
    public function getMyCategory(Request $request)
    {
        try {
            $response = $this->historyCategoryService->getAllCategoriesByUserAccountId(
                $request->userAccountId,
            );
            return response()->json(
                $response,
                Response::HTTP_OK
            );
        } catch (Exception $ex) {
            return response()->json(
                [
                    'message' =>  $ex->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
