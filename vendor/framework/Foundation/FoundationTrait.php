<?php

namespace MyPlugin\Framework\Foundation;

use MyPlugin\Framework\Foundation\ForbiddenException;

trait FoundationTrait
{
    public function env()
    {
        return $this->config->get('app.env');
    }

    public function getAjax($action, $handler, $isAdmin = true)
    {
        $action = $this->getAjaxAction($action, 'get', $isAdmin);

        return add_action($action, $this->parseAjaxHandler($handler));
    }

    public function getAjaxPublic($action, $handler)
    {
        $this->getAjax($action, $handler, false);
    }

    public function postAjax($action, $handler, $isAdmin = true)
    {
        $action = $this->getAjaxAction($action, 'post', $isAdmin);

        return add_action($action, function () use ($handler) {
            try {
                $slug = $this->config->get('app.slug');

                if (check_ajax_referer($slug, 'nonce', false)) {
                    return $this->parseAjaxHandler($handler)();
                }

                throw new ForbiddenException('Forbidden!', 401);
            } catch (ForbiddenException $e) {
                return $this->docustomAction('handle_exception', $e);
            }
        });
    }

    public function postAjaxPublic($action, $handler)
    {
        $this->postAjax($action, $handler, false);
    }

    public function getAjaxAction($action, $method, $isAdmin)
    {
        $prefix = $this->config->get('app.ajax_prefix');

        $context = $isAdmin ? 'wp_ajax_' : 'wp_ajax_nopriv_';

        return $context.$this->hook($prefix, $method.'-'.$action);
    }

    public function hook($prefix, $hook)
    {
        return $prefix . $hook;
    }

    public function parseAjaxHandler($handler)
    {
        if (!$handler) {
            return;
        }

        if (is_string($handler)) {
            $handler = $this->controllerNamespace . '\\' . $handler;
        } elseif (is_array($handler)) {
            list($class, $method) = $handler;
            if (is_string($class)) {
                $handler = $this->controllerNamespace . '\\' . $class . '::' . $method;
            }
        }

        return function () use ($handler) {
            return $this->call($handler);
        };
    }

    public function group($options = [], \Closure $callback = null)
    {
        return $this->rest->group($options, $callback);
    }

    public function get($route, $handler)
    {
        return $this->rest->get($route, $handler);
    }

    public function post($route, $handler)
    {
        return $this->rest->post($route, $handler);
    }

    public function put($route, $handler)
    {
        return $this->rest->put($route, $handler);
    }

    public function patch($route, $handler)
    {
        return $this->rest->patch($route, $handler);
    }

    public function delete($route, $handler)
    {
        return $this->rest->delete($route, $handler);
    }

    public function any($route, $handler)
    {
        return $this->rest->any($route, $handler);
    }

    public function parseRestHandler($handler)
    {
        if (!$handler) {
            return;
        }

        if ($this->hasNamespace($handler)) {
            return $handler;
        }

        if (is_string($handler)) {
            $handler = $this->controllerNamespace . '\\' . $handler;
        } elseif (is_array($handler)) {
            list($class, $method) = $handler;
            if (is_string($class)) {
                $handler = $this->controllerNamespace . '\\' . $class . '::' . $method;
            }
        }

        return $handler;
    }

    public function parsePolicyHandler($handler)
    {
        if (!$handler) {
            return;
        }

        if (is_string($handler)) {
            if ($this->hasNamespace($handler)) {
                $handler = $handler;
            } else {
                $handler = $this->policyNamespace . '\\' . $handler;
            }

            if ($this->isCallableWithAtSign($handler)) {
                list($class, $method) = explode('@', $handler);
                if (!method_exists($class, $method)) {
                    $method = 'verifyRequest';
                    if (!method_exists($class, $method)) {
                        $method = '__returnTrue';
                    }
                }
                $instance = $this->make($class);
                $handler = [$instance, $method];
            }
        } elseif (is_array($handler)) {
            list($class, $method) = $handler;

            if (is_string($class)) {
                if ($this->hasNamespace($handler)) {
                    $handler = $class . '::' . $method;
                } else {
                    $handler = $this->policyNamespace . '\\' . $class . '::' . $method;
                }
            }
        }

        return $handler;
    }

    public function addAction($action, $handler, $priority = 10, $numOfArgs = 1)
    {
        return add_action(
            $action,
            $this->parseHookHandler($handler),
            $priority,
            $numOfArgs
        );
    }

    public function addCustomAction($action, $handler, $priority = 10, $numOfArgs = 1)
    {
        $prefix = $this->config->get('app.hook_prefix');

        return $this->addAction(
            $this->hook($prefix, $action),
            $handler,
            $priority,
            $numOfArgs
        );
    }

    public function doAction()
    {
        return call_user_func_array('do_action', func_get_args());
    }

    public function doCustomAction()
    {
        $args = func_get_args();

        $prefix = $this->config->get('app.hook_prefix');

        $args[0] = $this->hook($prefix, $args[0]);

        return call_user_func_array('do_action', $args);
    }

    public function addFilter($action, $handler, $priority = 10, $numOfArgs = 1)
    {
        return add_filter(
            $action,
            $this->parseHookHandler($handler),
            $priority,
            $numOfArgs
        );
    }

    public function addCustomFilter($action, $handler, $priority = 10, $numOfArgs = 1)
    {
        $prefix = $this->config->get('app.hook_prefix');

        return $this->addFilter(
            $this->hook($prefix, $action),
            $handler,
            $priority,
            $numOfArgs
        );
    }

    public function applyFilters()
    {
        return call_user_func_array('apply_filters', func_get_args());
    }

    public function applyCustomFilters()
    {
        $args = func_get_args();
        $prefix = $this->config->get('app.hook_prefix');
        $args[0] = $this->hook($prefix, $args[0]);

        return call_user_func_array('apply_filters', $args);
    }

    public function parseHookHandler($handler)
    {
        if (is_string($handler)) {
            list($class, $method) = preg_split('/::|@/', $handler);

            if ($this->hasNamespace($handler)) {
                $class = $this->make($class);
            } else {
                $class = $this->make($this->handlerNamespace . '\\' . $class);
            }

            return [$class, $method];
        } elseif (is_array($handler)) {
            list($class, $method) = $handler;
            if (is_string($class)) {
                if ($this->hasNamespace($handler)) {
                    $class = $this->make($class);
                } else {
                    $class = $this->make($this->handlerNamespace . '\\' . $class);
                }
            }
            return [$class, $method];
        }

        return $handler;
    }

    public function hasNamespace($handler)
    {
        if ($handler instanceof \Closure) {
            return false;
        };

        $parts = explode('\\', $handler);
        return count($parts) > 1;
    }
}
