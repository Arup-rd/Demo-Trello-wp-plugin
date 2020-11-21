<?php
namespace MyPlugin\Http\Policies;

use MyPlugin\Framework\Foundation\Policy;

class ChartPolicy extends Policy
{
    public function index()
    {
        return $this->userAccessControl();
    }

    public function store()
    {
        return $this->userAccessControl();
    }

    public function find()
    {
        return $this->userAccessControl();
    }

    public function duplicate()
    {
        return $this->userAccessControl();
    }

    public function processData()
    {
        return $this->userAccessControl();
    }

    public function destroy()
    {
        return $this->userAccessControl();
    }

    public function userAccessControl()
    {
        if (is_user_logged_in()) {
            return current_user_can('manage_options');
        } else {
            return false;
        }
    }
}
