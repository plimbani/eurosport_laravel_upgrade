<?php

namespace Laraspace\Api\Services;

use Laraspace\Api\Contracts\TemplateContract;
use Laraspace\Api\Repositories\TemplateRepository;
use Laraspace\Traits\TournamentAccess;

class TemplateService implements TemplateContract
{
    use TournamentAccess;

    protected $templateRepoObj;

    public function __construct(TemplateRepository $templateRepoObj)
    {
        $this->getAWSUrl = getenv('S3_URL');
        $this->templateRepoObj = $templateRepoObj;
    }

    /*
     * Get all templates
     *
     * @param  array $api_key,$state,$type
     * @return response
     */
    public function getTemplates($data)
    {
        $data = $this->templateRepoObj->getTemplates($data);

        return ['data' => $data, 'status_code' => '200'];
    }

    /*
     * Get template detail
     *
     * @param  array $data
     * @return response
     */
    public function getTemplateDetail($data)
    {
        $data = $this->templateRepoObj->getTemplateDetail($data['templateData']['id']);

        return ['data' => $data, 'status_code' => '200'];
    }

    /*
     * Get users for filter
     *
     * @param  array $data
     * @return response
     */
    public function getUsersForFilter()
    {
        $data = $this->templateRepoObj->getUsersForFilter();

        return ['data' => $data, 'status_code' => '200'];
    }

    /*
     * Save template data
     *
     * @param  array $data
     * @return response
     */
    public function saveTemplateDetail($data)
    {
        $data = $this->templateRepoObj->saveTemplateDetail($data);

        return ['data' => $data, 'status_code' => '200'];
    }

    /*
     * Delete template
     *
     * @param  array $id
     * @return response
     */
    public function deleteTemplate($id)
    {
        $data = $this->templateRepoObj->deleteTemplate($id);
        if ($data) {
            return ['status_code' => '200', 'message' => 'Data Successfully Deleted'];
        }
    }

    /*
     * Edit template
     *
     * @param  array $id
     * @return response
     */
    public function editTemplate($id)
    {
        $templateData = $this->templateRepoObj->editTemplate($id);
        $tournamentsUsingTemplate = $this->templateRepoObj->getTemplateDetail($id);

        return ['data' => $templateData, 'isTemplateInUse' => (count($tournamentsUsingTemplate) === 0 ? false : true), 'status_code' => '200'];
    }

    /*
     * Update template data
     *
     * @param  array $data
     * @return response
     */
    public function updateTemplateDetail($data)
    {
        $data = $this->templateRepoObj->updateTemplateDetail($data);

        return ['data' => $data, 'status_code' => '200'];
    }

    private function saveTemplateGraphicImage($data, $id = '')
    {
        $graphicImage = $data['templateFormDetail']['stepone']['graphic_image'];
        if ($graphicImage != '') {
            if (strpos($graphicImage, $this->getAWSUrl) !== false) {
                $path = $this->getAWSUrl.'/assets/img/template_graphic_image/';
                $imageLogo = str_replace($path, '', $graphicImage);

                return $imageLogo;
            }

            $s3 = \Storage::disk('s3');
            $imagePath = '/assets/img/template_graphic_image/';
            $image_string = $graphicImage;
            $img = explode(',', $image_string);

            if (count($img) > 1) {
                $imgData = base64_decode($img[1]);
            } else {
                return '';
            }

            $f = finfo_open();
            $mimeType = finfo_buffer($f, $imgData, FILEINFO_MIME_TYPE);
            $split = explode('/', $mimeType);
            $extension = $split[1];

            $imageName = \Uuid::generate(4);
            $path = $imagePath.$imageName.'.'.$extension;
            $s3->put($path, $imgData);

            return $imageName.'.'.$extension;
        } else {
            return '';
        }
    }

    /*
     * Get template graphic
     *
     * @return response
     */
    public function getTemplateGraphic($request)
    {
        $data = $this->templateRepoObj->getTemplateGraphic($request);

        return ['data' => $data, 'status_code' => '200'];
    }

    /*
     * Get template graphic of league
     *
     * @return response
     */
    public function getTemplateGraphicOfLeague($request)
    {
        $data = $this->templateRepoObj->getTemplateGraphicOfLeague($request);

        return ['data' => $data, 'status_code' => '200'];
    }
}
