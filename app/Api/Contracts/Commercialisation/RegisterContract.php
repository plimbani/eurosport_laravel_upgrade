<?php

namespace Laraspace\Api\Contracts\Commercialisation;

interface RegisterContract
{
	/*
     * Get Register Page
     *
     * @param void
     * @return view
     */
	public function index();
	 
	 /*
     * Register New Customer
     *
     * @param  $request 
     * @return 
     */

    public function register($request);
}
?>