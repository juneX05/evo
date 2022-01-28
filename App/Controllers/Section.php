<?php
/*
 * This file is part of the Evo package.
 *
 * (c) John Andrew <simplygenius78@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types = 1);

namespace App\Controllers;

use App\Auth;
use App\Flash;
use App\Models\MenuModel;
use App\Models\SectionModel;
use Evo\View;
use Throwable;

class Section extends Authenticated
{
    public function index()
    {
        // retrieve details of all sections from the DB
        $sections = (new SectionModel)->getRepository()->findAll();
        $menus = (new MenuModel)->getRepository()->findAll();

        View::renderTemplate('section/index.html', [
            'sections' => $sections,
            'menus' => $menus,
        ]);
    }

    public function create()
    {
        // display a form to create a new section
        View::renderTemplate('section/create.html');
    }

    public function show()
    {
        // retrieve details of a single section from the DB
        View::renderTemplate('section/show.html');
    }

    /**
     * @throws Throwable
     */
    public function add()
    {
        echo '<pre>';
        $clean_data = (new SectionModel)->cleanData($_POST)->save();

        if ($clean_data) {
            Flash::addMessageToFlashNotifications('Section added successfully');

        } else {

            Flash::addMessageToFlashNotifications('Failed to add the section', Flash::WARNING);

        }
    }

    public function edit()
    {
        // display a form to update a section
        View::renderTemplate('section/edit.html');
    }

    public function update()
    {
        // updates a section in the DB
    }

    public function delete()
    {
        // deletes a section from the DB
    }
}
