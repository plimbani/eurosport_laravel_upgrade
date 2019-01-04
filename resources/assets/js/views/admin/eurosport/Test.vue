<template>
  <section class="main-section">
    <!-- Section Login Started -->
        <div class="login-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-9 col-lg-6">
                        <h6 class="text-uppercase mb-0">For Members</h6>
                        <h1 class="font-weight-bold">Login</h1>
                        <p class="mb-0">Quisque vel nulla at nibh finibus sodales. Nam efficitur sem. Don’t have an account? <a href="#">Register here</a>.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="divider my-5"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9 col-lg-6">
                        <form class="login-form">
                            <div class="form-group">
                                <label for="email-id">Email Address</label>
                                <input type="email" class="form-control" id="email-id" placeholder="e.g name@domain.com">
                            </div>

                            <div class="form-group">
                                <label for="pwd">Password</label>
                                <input type="password" class="form-control" id="pwd">
                            </div>

                            <div class="row">
                                <div class="col-lg-5 col-md-6">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-block">Login</button>
                                    </div>
                                </div>
                            </div>

                            <p class="h7">Forgot your password? <a href="#">Reset it here</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <!-- Section Login Ended -->

    <!-- Section Confirmation Started -->
        <div class="confirmation-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-9 col-lg-6">
                        <h6 class="text-uppercase mb-0">Registration Complete</h6>
                        <h1 class="font-weight-bold">Thank You</h1>
                        <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris posuere vel mi ac sagittis. Quisque vel nulla at nibh finibus sodales. Nam efficitur sem a mi rhoncus. If you have an immediate enquiry please call   <a href="tel:+44(0)1234 567 890">+44(0)1234 567 890</a></p>

                        <div class="row">
                            <div class="col-lg-5 col-md-6">
                                <button class="btn btn-success btn-block">Buy a License</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Section Confirmation Ended -->

    <!-- Section Buy License Started -->
        <div class="buy-license-section section-padding">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-lg-6">
                        <h1 class="font-weight-bold">Buy a License</h1>
                        <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris posuere vel mi ac sagittis. Quisque vel nulla at nibh finibus sodales. Nam efficitur sem a mi rhoncus. </p>

                        <h4 class="text-uppercase font-weight-bold">Number of teams competing</h4>

                        <p>------- Range Slider --------</p>

                        <h4 class="text-uppercase font-weight-bold">When will the tournament run?</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control" id="start-date">
                                        <option selected>Start date</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control" id="end-date">
                                        <option selected>End date</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tournament-name">Name of your tournament</label>
                            <input type="text" class="form-control" id="tournament-name">
                        </div>
                    </div>
                    <div class="col-lg-5 mt-3 mt-lg-0">
                        <div class="card shaded-card">
                            <div class="card-block">
                                <div class="card-title">
                                    <div class="row align-items-center">
                                        <div class="col-md-6 col-lg-7">
                                            <h3 class="mb-0 text-uppercase font-weight-bold">Your Cart</h3>
                                        </div>
                                        <div class="col-md-6 col-lg-5 mt-2 mt-md-0">
                                            <select class="form-control" id="gbp">
                                                <option selected>GBP</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider my-3"></div>

                                <div class="card-text">
                                  <div class="row">
                                    <div class="col-sm-6 col-md-7 col-lg-7">
                                      <p class="mb-0">32 team license for a 4 day tournament</p>
                                    </div>
                                    <div class="col-sm-6 col-md-5 col-lg-5">
                                      <p class="text-sm-right mb-0 mt-3 mt-sm-0">£100.00</p>
                                    </div>
                                  </div>
                                  <div class="divider my-3"></div>
                                  <p class="text-sm-right font-weight-bold">£100.00</p>
                                </div>
                                <div class="row justify-content-end">
                                    <div class="col-md-7 col-lg-7 col-xl-6">
                                        <button class="btn btn-success btn-block">Buy your license</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Section Buy License Ended -->

    <!-- Section Register Started -->
        <div class="register-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <form class="registration-form">
                            <div class="row">
                                <div class="col-md-9 col-lg-6">
                                    <h1 class="font-weight-bold">Register</h1>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris posuere vel mi ac sagittis. Quisque vel nulla at nibh finibus sodales. Nam efficitur sem a mi rhoncus. If you already have an account you can  <a href="#">login here</a>.</p>

                                    <h3 class="text-uppercase font-weight-bold mt-5">Your details</h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="divider mb-5"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-9 col-lg-6">
                                    <div class="form-group">
                                        <label>Your Name</label>
                                        <input type="text" class="form-control mb-4" id="fname" placeholder="First name">
                                        <input type="text" class="form-control" id="lname" placeholder="Last name">
                                    </div>

                                    <div class="form-group">
                                        <label for="email-id">Email Address</label>
                                        <input type="email" class="form-control" id="email-id" placeholder="e.g name@domain.com">
                                    </div>

                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control mb-4" id="pwd">
                                        <input type="password" class="form-control" id="cpwd" placeholder="Confirm Password">
                                    </div>

                                    <h3 class="text-uppercase font-weight-bold mt-5">Your organisation</h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="divider mb-5"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-9 col-lg-6">
                                    <div class="form-group">
                                        <label for="company-name">Organisation or Company name</label>
                                        <input type="text" class="form-control" id="company-name">
                                    </div>

                                    <div class="form-group">
                                        <label for="job-title">Your Job title</label>
                                        <input type="text" class="form-control" id="job-title">
                                    </div>

                                    <h3 class="text-uppercase font-weight-bold mt-5">Your address</h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="divider mb-5"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-9 col-lg-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" class="form-control mb-4" id="address-line-1">
                                        <input type="text" class="form-control" id="address-line-2">
                                    </div>

                                    <div class="form-group">
                                        <label for="city">Town or city</label>
                                        <input type="text" class="form-control" id="city">
                                    </div>

                                    <div class="form-group">
                                        <label for="zipcode">Zip or postcode</label>
                                        <input type="text" class="form-control" id="zipcode">
                                    </div>

                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <select class="form-control" id="country">
                                            <option selected>Please Select</option>
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                        </select>
                                    </div>

                                    <div class="row mt-5">
                                        <div class="col-lg-9 col-xl-8">
                                            <div class="form-group">
                                                <button class="btn btn-success btn-block">Register with Easy Match Manager</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <!-- Section Register Ended -->

    <!-- Section Manage Tournament Started -->
        <div class="manage-tournament section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-uppercase font-weight-bold mb-4">Manage Tournaments</h3>

                        <div class="row">
                            <div class="col-md-6 col-lg-12 d-flex">
                                <div class="card w-100">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-xl-7">
                                                <p class="h7 text-uppercase mb-0">License: #000987</p>
                                                <div class="row align-items-center mb-2">
                                                    <div class="col-lg-7">
                                                        <h3 class="font-weight-bold mb-0">Brighton Champions Cup</h3>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <ul class="list-unstyled mb-0 tournament-information">
                                                            <li class="d-inline h7 text-uppercase font-weight-bold pr-2"><span><i class="fa fa-globe"></i></span>&nbsp; <a href="#">View public website</a></li>
                                                            <li class="d-inline h7 text-uppercase font-weight-bold"><span><i class="fa fa-share-alt"></i></span>&nbsp; <a href="#">Share</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><i class="fa fa-list-ul" aria-hidden="true"></i> &nbsp;32 Teams</li>
                                                    <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><i class="fa fa-futbol-o" aria-hidden="true"></i> &nbsp;48 Matches</li>
                                                    <li class="d-block d-lg-inline"><i class="fa fa-calendar" aria-hidden="true"></i> &nbsp;19th - 22nd October 2018</li>
                                                </ul>
                                            </div>
                                            <div class="col-xl-5 mt-3 mt-lg-0 text-lg-right">
                                                <div class="btn-group">
                                                    <button class="btn btn-outline"><span><i class="fa fa-pencil" aria-hidden="true"></i></span>&nbsp; Edit</button>
                                                    <button class="btn btn-outline ml-2">Manage License</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-12 d-flex">
                                <div class="card w-100">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-xl-7">
                                                <p class="h7 text-uppercase mb-0">License: #000987</p>
                                                <div class="row align-items-center mb-2">
                                                    <div class="col-lg-7">
                                                        <h3 class="font-weight-bold mb-0">Brighton Champions Cup</h3>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <ul class="list-unstyled mb-0 tournament-information">
                                                            <li class="d-inline h7 text-uppercase font-weight-bold pr-2"><span><i class="fa fa-globe"></i></span>&nbsp; <a href="#">View public website</a></li>
                                                            <li class="d-inline h7 text-uppercase font-weight-bold"><span><i class="fa fa-share-alt"></i></span>&nbsp; <a href="#">Share</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><i class="fa fa-list-ul" aria-hidden="true"></i> &nbsp;32 Teams</li>
                                                    <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><i class="fa fa-futbol-o" aria-hidden="true"></i> &nbsp;48 Matches</li>
                                                    <li class="d-block d-lg-inline"><i class="fa fa-calendar" aria-hidden="true"></i> &nbsp;19th - 22nd October 2018</li>
                                                </ul>
                                            </div>
                                            <div class="col-xl-5 mt-3 mt-lg-0 text-lg-right">
                                                <div class="btn-group">
                                                    <button class="btn btn-outline"><span><i class="fa fa-pencil" aria-hidden="true"></i></span>&nbsp; Edit</button>
                                                    <button class="btn btn-outline ml-2">Manage License</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-success">Add Tournament</button>

                        <h3 class="text-uppercase font-weight-bold mb-4 mt-5">Manage Templates</h3>
                        <div class="row">
                            <div class="col-md-6 col-lg-12 d-flex">
                                <div class="card w-100">
                                    <div class="card-block">
                                        <div class="row align-items-center">
                                            <div class="col-xl-7">
                                                <p class="h7 mb-0">Version 1</p>
                                                <h3 class="font-weight-bold mb-2">Ben’s 4x4 + Knockout</h3>
                                                <ul class="list-unstyled mb-0">
                                                    <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><i class="fa fa-list-ul" aria-hidden="true"></i> &nbsp;32 Teams</li>
                                                    <li class="d-block d-lg-inline pb-1 pb-sm-0 pr-sm-2"><i class="fa fa-futbol-o" aria-hidden="true"></i> &nbsp;48 Matches</li>
                                                    <li class="d-block d-lg-inline"><i class="fa fa-calendar" aria-hidden="true"></i> &nbsp;19th - 22nd October 2018</li>
                                                </ul>
                                            </div>
                                            <div class="col-xl-5 mt-3 mt-lg-0 text-lg-right">
                                                <div class="btn-group">
                                                    <button class="btn btn-link"><span><i class="fa fa-trash" aria-hidden="true"></i></span>&nbsp;Delete</button>
                                                    <button class="btn btn-outline ml-2"><span><i class="fa fa-pencil" aria-hidden="true"></i></span>&nbsp; Edit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success">Add Template</button>
                    </div>
                </div>
            </div>
        </div>
    <!-- Section Manage Tournament Ended -->

    <!-- Section Tournament Started -->
        <div class="tournament-section section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8">
                        <div class="row">
                            <div class="col-sm-4 col-md-3">
                                <img src='/images/dummy.png' class="img-fluid tournament-image">
                            </div>
                            <div class="col-sm-8 col-md-9">
                                <h6 class="text-uppercase mb-0 mt-4 mt-sm-0">License: #000987</h6>
                                <h2 class="font-weight-bold mb-0">Brighton Champions Cup</h2>
                                <h4 class="text-uppercase font-weight-bold mb-4">19th - 22nd October 2018</h4>

                                <h6 class="text-uppercase mb-0 font-weight-bold">Main Contact</h6>
                                <p class="mb-4">Ben Grout <a href="tel:+44 7557 123 456">+44 7557 123 456</a></p>

                                <h6 class="text-uppercase font-weight-bold">Sponsored by</h6>

                                <ul class="list-unstyled sponsored-list mb-0">
                                    <li class="d-inline"><img src="/images/macd-logo.png" alt="sponsored"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 bordered-div mt-5 mt-xl-0">
                        <h4 class="text-uppercase font-weight-bold">Follow on your phone</h4>
                        <p class="mb-4">Download and open the tournament planner app and enter the following code to follow Brighton Champions Cup.</p>

                        <div class="app-code text-center py-3">
                            <h3 class="font-weight-bold m-0">XY99</h3>
                        </div>

                        <ul class="list-unstyled get-app mb-0 text-xl-center mt-4">
                            <li class="d-inline pr-2"><a href="#"><img src="/images/app-store.png"></a></li>
                            <li class="d-inline"><a href="#"><img src="/images/google-play.png"></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <!-- Section Tournament Ended -->

    <!-- Section Schedule & Results Started -->
        <div class="schedule-result section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-uppercase font-weight-bold">Schedule & Results</h3>
                        <h6 class="text-uppercase font-weight-bold mb-0">Last Updated</h6>
                        <p class="mb-4">19th October 2018, 09:43</p>

                        <div class="tabs tabs-primary">
                            <ul role="tablist" class="nav nav-tabs">
                                <li class="nav-item">
                                    <a data-toggle="tab" href="#categories" role="tab" class="active nav-link">Categories</a>
                                </li>
                                <li class="nav-item">
                                    <a data-toggle="tab" href="#matches" role="tab" class="nav-link">Matches</a>
                                </li>
                                <li class="nav-item">
                                    <a data-toggle="tab" href="#teams" role="tab" class="nav-link">Teams</a>
                                </li>
                                <li class="nav-item">
                                    <a data-toggle="tab" href="#final-placing" role="tab" class="nav-link">Final Placing</a>
                                </li>
                            </ul>
                            <div id="results" class="tab-content mt-0">
                                <div class="tab-pane fade show active" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Teams</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><a href="#">U15-A</a></td>
                                                <td>18</td>
                                            </tr>
                                            <tr>
                                                <td><a href="#">U15-B</a></td>
                                                <td>18</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane fade" id="matches" role="tabpanel" aria-labelledby="matches-tab">
                                    <p>------- Matches Tab -------</p>
                                </div>
                                <div class="tab-pane fade" id="teams" role="tabpanel" aria-labelledby="teams-tab">
                                    <p>------- Teams Tab -------</p>
                                </div>
                                <div class="tab-pane fade" id="final-placing" role="tabpanel" aria-labelledby="final-placing-tab">
                                    <p>------- Final Placing Tab -------</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Section Schedule & Results Ended -->

    <!-- Section Components Started -->
        <div class="block-component section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="component-typography">
                            <h4>Typography</h4>

                            <h1>Heading - 48px</h1>
                            <h2>Heading - 36px</h2>
                            <h3>Heading - 24px</h3>
                            <h4>Heading - 18px</h4>
                            <h5>Heading - 16px</h5>
                            <h6>Heading - 14px</h6>
                            <h6 class="h7">Heading - 12px</h6>
                            <h6 class="h8">Heading - 11px</h6>
                        </div>

                        <div class="component-breadcrumb mt-5">
                            <h4>Breadcrumb</h4>
                            <div class="page-header">
                                <ol class="breadcrumb">
                                    <li><a href="#">Home</a></li>
                                    <li><a href="#">Brabant Girls Cup</a></li>
                                    <li class="active"><span>Edit Tournament</span></li>
                                </ol>
                            </div>
                        </div>

                        <div class="component-button mt-5">
                            <h4>Buttons</h4>
                            <button class="btn btn-primary">Primary</button>
                            <button class="btn btn-success">Success</button>
                            <button class="btn btn-danger">Danger</button>
                            <button class="btn btn-warning">Warning</button>
                            <button class="btn btn-secondary">Secondary</button>
                        </div>

                        <div class="component-tab mt-5">
                            <h4>Tabs</h4>
                            <div class="tabs tabs-primary">
                                <ul role="tablist" class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a data-toggle="tab" href="#one" role="tab" class="active nav-link">One</a>
                                    </li>
                                    <li class="nav-item">
                                        <a data-toggle="tab" href="#two" role="tab" class="nav-link">Two</a>
                                    </li>
                                    <li class="nav-item">
                                        <a data-toggle="tab" href="#three" role="tab" class="nav-link">Three</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="component-table mt-5">
                            <h4>Table</h4>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Category name</th>
                                        <th>Age category</th>
                                        <th>Template name</th>
                                        <th>Competition format</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>G15 </td>
                                        <td>G15</td>
                                        <td>T.6.5</td>
                                        <td>6 teams: 1x6 - F</td>
                                    </tr>
                                    <tr>
                                        <td>G15 </td>
                                        <td>G15</td>
                                        <td>T.6.5</td>
                                        <td>6 teams: 1x6 - F</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="component-form mt-5">
                            <h4>Form Controls</h4>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" placeholder="Name">
                            </div>

                            <div class="form-group">
                                <label for="country">Country</label>
                                <select class="form-control" id="country">
                                    <option selected>Select</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name">
                            </div>

                            <div class="form-group">
                                <div class="radio">
                                    <input type="radio" name="btn-radio" id="btn-radio" class="euro-radio" value="">
                                    <label for="btn-radio">Radio Button</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input euro-checkbox" type="checkbox" id="inlineCheckbox1" value="option1">
                                    <label class="form-check-label" for="inlineCheckbox1">1</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input euro-checkbox" type="checkbox" id="inlineCheckbox2" value="option2">
                                    <label class="form-check-label" for="inlineCheckbox2">2</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input euro-checkbox" type="checkbox" id="inlineCheckbox3" value="option3" disabled>
                                    <label class="form-check-label" for="inlineCheckbox3">3 (disabled)</label>
                                </div>
                            </div>
                        </div>

                        <div class="component-card mt-5">
                            <h4>Card</h4>
                            <div class="card w-25 shaded-card">
                                <div class="card-block">
                                    <h5 class="card-title">Special title treatment</h5>
                                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>
                        </div>

                        <div class="component-modal mt-5">
                            <h4>Modal</h4>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                              Launch demo modal
                            </button>
                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                            tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                            consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                                            cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                                            proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <!-- Section Components Ended -->
  </section>
</template>
