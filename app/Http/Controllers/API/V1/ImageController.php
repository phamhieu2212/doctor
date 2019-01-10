<?php
namespace App\Http\Controllers\API\V1;
use App\Http\Controllers\Controller;
use App\Http\Responses\API\V1\Response;
use App\Http\Requests\BaseRequest;
use App\Services\FileUploadServiceInterface;
use App\Repositories\ImageRepositoryInterface;

class ImageController extends Controller {
     /** @var FileUploadServiceInterface $fileUploadService */
     protected $fileUploadService;

     /** @var ImageRepositoryInterface $imageRepository */
     protected $imageRepository;

     public function __construct(
        FileUploadServiceInterface  $fileUploadService,
        ImageRepositoryInterface    $imageRepository
    ) {
        $this->fileUploadService    = $fileUploadService;
        $this->imageRepository      = $imageRepository;
    }

    public function upload(BaseRequest $request)
    {
        if( $request->hasFile( 'image' ) ) {
            $file = $request->file( 'image' );
            $newImage = $this->fileUploadService->upload(
                'image',
                $file,
                [
                    'entityType' => 'image',
                    'entityId'   => 0,
                    'title'      => null,
                ]
            );

            if (empty($newImage)) {
                return Response::response(50002);
            }

            return Response::response(200, ['id' => $newImage->id, 'url' => $newImage->present()->url]); 
        }
    }

    public function delete($id)
    {
        if( !is_numeric($id) || ($id <= 0) ) {
            return Response::response(40001);
        }

        $image = $this->imageRepository->find($id);

        if (empty($image)) {
            return Response::response(20004);
        }

        $this->fileUploadService->delete( $image );
        $this->imageRepository->delete( $image );

        return Response::response(200, ['success' => true]); 
    }
}
