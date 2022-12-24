<?php

namespace App\Http\Controllers;

use \Exception;

use Illuminate\Http\Response;

use App\Services\Announcement\AnnouncementService;
use App\Http\Requests\Announcement\AnnouncementAddRequest;
use App\Http\Requests\Announcement\AnnouncementEditRequest;
use App\Http\Requests\Announcement\AnnouncementDeleteRequest;
use App\Http\Requests\Announcement\AnnouncementGetByIdRequest;
use App\Http\Response\Announcement\RecentAnnouncementGetByIdResponse;
use App\Http\Response\Announcement\RecentAnnouncementResponse;

class AnnouncementController extends Controller
{
    private $announcementService;
    public function __construct(
        AnnouncementService $announcementService,
    ) {
        $this->announcementService = $announcementService;
    }

    // お知らせの新規登録
    public function addAnnouncement(AnnouncementAddRequest $request)
    {
        try {
            $this->announcementService->createAnnouncement(
                $request->title,
                $request->announcementHtml
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

    // お知らせの編集
    public function editAnnouncement(AnnouncementEditRequest $request)
    {
        try {
            $this->announcementService->editAnnouncement(
                $request->id,
                $request->title,
                $request->announcementHtml
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

    // お知らせの削除
    public function deleteAnnouncement(AnnouncementDeleteRequest $request)
    {
        try {
            $this->announcementService->deleteAnnouncement(
                $request->id,
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

    // お知らせの一覧取得
    public function getRecentAnnouncements()
    {
        try {
            $results = $this->announcementService->getRecentAnnouncements();
            return response()->json(
                collect($results)->map(function ($item) {
                    return new RecentAnnouncementResponse(
                        $item->id,
                        $item->title,
                        $item->createdAt,
                    );;
                })->all(),
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

    // id指定でお知らせ取得
    public function getAnnouncementById(AnnouncementGetByIdRequest $request)
    {
        try {
            $result = $this->announcementService->getAnnouncementById($request->id);
            if (!isset($result)) {
                return response()->json(
                    [
                        'message' =>  "Not found announcement"
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }

            return response()->json(
                new RecentAnnouncementGetByIdResponse(
                    $result->id,
                    $result->title,
                    $result->announcementHtml,
                ),
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
