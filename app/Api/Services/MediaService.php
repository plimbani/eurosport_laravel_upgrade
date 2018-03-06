<?php

namespace Laraspace\Api\Services;

use Storage;
use Config;
use Laraspace\Api\Contracts\MediaContract;
use Laraspace\Api\Repositories\MediaRepository;
use Laraspace\Custom\Helper\Image;
use Laraspace\Jobs\ImageConversion;

class MediaService implements MediaContract
{
  /**
   * @var MediaRepository
   */
  protected $mediaRepo;

  /**
   * @var local temperary image destination
   */
  protected $tempImagePath;

  /**
   * @var predefined image path
   */
  protected $imagePath;

  /**
   * @var predefined conversion sizes
   */
  protected $conversions;

  /**
   * @var predefined s3AWS url
   */
  protected $getAWSUrl;

	/**
   *  Success message
   */
  const SUCCESS_MSG = 'Data Sucessfully inserted';

  /**
   *  Error message
   */
  const ERROR_MSG = 'Error in Data';

  /**
   * Create a new controller instance.
   *
   * @param MediaRepository $mediaRepo
   */
  public function __construct(MediaRepository $mediaRepo)
  {
    $this->getAWSUrl = getenv('S3_URL');
    $this->mediaRepo = $mediaRepo;
    $this->tempImagePath = Config::get('wot.tempImagePath');
    $this->imagePath = Config::get('wot.imagePath');
    $this->conversions = Config::get('image-conversion.conversions');
  }

  /*
   * Get statistics
   *
   * @return response
   */
  public function getPhotos($websiteId)
  {
    $data = $this->mediaRepo->getAllPhotos($websiteId);
    
    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Get organisers
   *
   * @return response
   */
  public function getDocuments($websiteId)
  {
    $data = $this->mediaRepo->getAllDocuments($websiteId);
    
    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Save page data
   *
   * @return response
   */
  public function savePageData($data)
  {
    $data = $this->mediaRepo->savePageData($data);

    return ['data' => $data, 'status_code' => '200', 'message' => 'All data'];
  }

  /*
   * Save media photo image
   *
   * @return response
   */
  public function uploadMediaPhoto($request)
  {
    $filename = Image::createTempImage($request->image);
    $localpath = $this->tempImagePath.$filename;
    $s3path = $this->imagePath['photo'].$filename;

    // moving main image file to S3
    $disk = Storage::disk('s3');
    $disk->put($s3path, file_get_contents($localpath), 'public');

    ImageConversion::dispatch(
      $filename, 
      $this->tempImagePath, 
      $this->imagePath['photo'], 
      $this->conversions['photo']
    );

    return $this->getAWSUrl . $s3path;
  }

  /*
   * Save media document
   *
   * @return response
   */
  public function uploadDocument($request)
  {
    $websiteId = $request['websiteId'];
    $filename = pathinfo($request->file->getClientOriginalName(), PATHINFO_FILENAME);
    $extension = pathinfo($request->file->getClientOriginalName(), PATHINFO_EXTENSION);
    $fileNameWithExtension = $filename . '_' . uniqid() . '_' . time() . '_' . date('Ymd') . rand(10,99) . '.' . $extension;
    $s3path = $this->imagePath['document'] . $websiteId . '/' . $fileNameWithExtension;

    // Moving main image file to S3
    $disk = Storage::disk('s3');
    $disk->put($s3path, file_get_contents($request->file), 'public');

    return $this->getAWSUrl . $s3path;
  }

}
