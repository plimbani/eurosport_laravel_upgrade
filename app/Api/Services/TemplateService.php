<?php

namespace Laraspace\Api\Services;

use Laraspace\Traits\TournamentAccess;
use Laraspace\Api\Contracts\TemplateContract;
use Laraspace\Api\Repositories\TemplateRepository;

class TemplateService implements TemplateContract
{
	use TournamentAccess;

    protected $templateRepoObj;

    public function __construct(TemplateRepository $templateRepoObj)
    {
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
        $data = $this->templateRepoObj->getTemplateDetail($data);
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
    public function saveTemplateDetail($data) {
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
        $data = $this->templateRepoObj->editTemplate($id);
        return ['data' => $data, 'status_code' => '200'];
    }

    /*
     * Update template data
     *
     * @param  array $data
     * @return response
     */
    public function updateTemplateDetail($data) {
        $data = $this->templateRepoObj->updateTemplateDetail($data);
        return ['data' => $data, 'status_code' => '200'];
    }    
}