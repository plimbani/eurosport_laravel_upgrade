<?php

namespace Laraspace\Api\Repositories;

use DB;
use Laraspace\Models\Photo;
use Laraspace\Models\Document;
use Laraspace\Custom\Helper\Image;
use Laraspace\Traits\AuthUserDetail;
use Laraspace\Api\Services\PageService;

class MediaRepository
{
  use AuthUserDetail;

  /**
   * @var AWS URL
   */
  protected $getAWSUrl;

  /**
   * @var Page service
   */
  protected $pageService;

  /**
   * @var Page name
   */
  protected $pageName;

  /**
   * Create a new controller instance.
   */
  public function __construct(PageService $pageService)
  {
    $this->getAWSUrl = getenv('S3_URL');
    $this->pageService = $pageService;
    $this->pageName = 'media';
  }

  /*
   * Get all photos
   *
   * @return response
   */
  public function getAllPhotos($websiteId)
  {
    $photos = Photo::where('website_id', $websiteId)->orderBy('order')->get();

    return $photos;
  }

  /*
   * Get all photo ids
   *
   * @return response
   */
  public function getAllPhotoIds($websiteId)
  {
    $photoIds = Photo::where('website_id', $websiteId)->pluck('id')->toArray();
    return $photoIds;
  }

  /*
   * Insert photo
   *
   * @return response
   */
  public function insertPhoto($websiteId, $currentLoggedInUserId, $data)
  {
    $photo = new Photo();
    $photo->website_id = $websiteId;
    $photo->caption = $data['caption'];
    $photo->order = $data['order'];
    $photo->image = $data['image'];
    $photo->created_by = $currentLoggedInUserId;
    $photo->save();

    return $photo;
  }

  /*
   * Update photo
   *
   * @return response
   */
  public function updatePhoto($currentLoggedInUserId, $data)
  {
    $photo = Photo::find($data['id']);
    $photo->caption = $data['caption'];
    $photo->order = $data['order'];
    $photo->image = $data['image'];
    if($photo->isDirty()) {
      $photo->updated_by = $currentLoggedInUserId;
      $photo->save();
    }

    return $photo;
  }

  /*
   * Update photo
   *
   * @return response
   */
  public function deletePhoto($photoId)
  {
    $photo = Photo::find($photoId);
    if($photo->delete()) {
      return true;
    }
    return false;
  }

  /*
   * Delete multiple photos
   *
   * @return response
   */
  public function deletePhotos($photoIds = [])
  {
    Photo::whereIn('id', $photoIds)->get()->each(function($photo) {
      $photo->delete();
    });
    return true;
  }

  /*
   * Get page data
   *
   * @return response
   */
  public function savePageData($data)
  {
    $this->savePhotos($data);
    $this->saveDocuments($data);
  }

  /*
   * Save photos
   *
   * @return response
   */
  public function savePhotos($data)
  {
    $websiteId = $data['websiteId'];
    $photos = $data['photos'];

    $existingPhotosId = $this->getAllPhotoIds($websiteId);

    $photoIds = [];
    for($i=0; $i<count($photos); $i++) {
      $photoData = $photos[$i];
      $photoData['order'] = $i + 1;

      // Upload image
      $photoData['image'] = basename(parse_url($photoData['image'])['path']);
      $currentLoggedInUserId = $this->getCurrentLoggedInUserId();
      if($photoData['id'] == '') {
        $photo = $this->insertPhoto($websiteId, $currentLoggedInUserId, $photoData);
      } else {
        $photo = $this->updatePhoto($currentLoggedInUserId, $photoData);
      }
      $photoIds[] = $photo->id;
    }

    $deletePhotosId = array_diff($existingPhotosId, $photoIds);

    $this->deletePhotos($deletePhotosId);
  }

  /*
   * Get all documents
   *
   * @return response
   */
  public function getAllDocuments($websiteId)
  {
    $documents = Document::where('website_id', $websiteId)->get();

    return $documents;
  }

  /*
   * Get all document ids
   *
   * @return response
   */
  public function getAllDocumentIds($websiteId)
  {
    $documentIds = Document::where('website_id', $websiteId)->pluck('id')->toArray();
    return $documentIds;
  }

  /*
   * Insert document
   *
   * @return response
   */
  public function insertDocument($websiteId, $currentLoggedInUserId, $data)
  {
    $document = new Document();
    $document->website_id = $websiteId;
    $document->file_name = $data['file_name'];
    $document->created_by = $currentLoggedInUserId;
    $document->save();

    return $document;
  }

  /*
   * Update document
   *
   * @return response
   */
  public function updateDocument($currentLoggedInUserId, $data)
  {
    $document = Document::find($data['id']);
    $document->file_name = $data['file_name'];
    if($document->isDirty()) {
      $document->updated_by = $currentLoggedInUserId;
      $document->save();
    }

    return $document;
  }

  /*
   * Update document
   *
   * @return response
   */
  public function deleteDocument($documentId)
  {
    $document = Document::find($documentId);
    if($document->delete()) {
      return true;
    }
    return false;
  }

  /*
   * Delete multiple documents
   *
   * @return response
   */
  public function deleteDocuments($documentIds = [])
  {
    Document::whereIn('id', $documentIds)->get()->each(function($document) {
      $document->delete();
    });
    return true;
  }

  /*
   * Save documents
   *
   * @return response
   */
  public function saveDocuments($data)
  {
    $websiteId = $data['websiteId'];
    $documents = $data['documents'];

    $existingDocumentsId = $this->getAllDocumentIds($websiteId);

    $documentIds = [];
    for($i=0; $i<count($documents); $i++) {
      $documentData = $documents[$i];

      // Upload file
      $documentData['file_name'] = basename(parse_url($documentData['file'])['path']);
      $currentLoggedInUserId = $this->getCurrentLoggedInUserId();
      if($documentData['id'] == '') {
        $document = $this->insertDocument($websiteId, $currentLoggedInUserId, $documentData);
      } else {
        $document = $this->updateDocument($currentLoggedInUserId, $documentData);
      }
      $documentIds[] = $document->id;
    }

    $deleteDocumentsId = array_diff($existingDocumentsId, $documentIds);

    $this->deleteDocuments($deleteDocumentsId);
  }
}
