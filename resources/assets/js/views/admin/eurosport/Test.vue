<template>
    <section class="block-component section-padding">
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
                            <label class="radio-inline control-label">
                                <input type="radio" id="category-radio-button1" name="category-radio-button1" value="">Category
                            </label>

                            <label class="radio-inline control-label">
                                <input type="radio" id="category-radio-button2" name="category-radio-button1" value="">Category
                            </label>

                            <label class="radio-inline control-label">
                                <input type="radio" id="category-radio-button3" name="category-radio-button1" value="">Category
                            </label>
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
                            <div class="card-body">
                                <h5 class="card-title">Special title treatment</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>

                    <div class="component-modal mt-5">
                        <h4>Modal</h4>
                        <!-- Button trigger modal -->
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

                    <div class="component-range-slider mt-5 bg-danger">

                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
