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
    public function getTemplates()
    {
       $data = $this->templateRepoObj->getTemplates();
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
}