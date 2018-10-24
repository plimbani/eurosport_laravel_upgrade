<?php

namespace Laraspace\Api\Contracts;

interface TemplateContract
{
    /*
     * Get all templates
     *
     * @return response
     */
    public function getTemplates();

    /*
     * Get template detail
     *
     * @param  $data
     * @return response
     */
    public function getTemplateDetail($data);
}