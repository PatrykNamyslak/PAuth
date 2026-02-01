<?php
namespace PatrykNamyslak\Auth\Blueprints;

use PatrykNamyslak\Auth\Events\Dispatcher;

abstract class ServiceProvider{
    /**
     * Set the events to listen to along side the Listener class that is fired upon dispatch event
     * Example Usage:
     * protected array $listen =[
     *      LoginFailed::class => [
     *          NotifyUser::class,
     *          LogAttempt::class,
     *      ],
     * ...
     * ]
     */
    abstract protected array $listen;


    public function __construct(
        protected Dispatcher $dispatcher,
    ){}

    /**
     * Register all event listeners defined in $listen array
     * @return void
     */
    public function register(){
        foreach($this->listen as $event => $listeners){
            foreach($listeners as $listener){
                $this->subscribe($event, $listener);
            }
        }
        $this->boot();
    }

    /**
     * Subscribes all of the listeners to the dispatcher from the $listen array
     * @param string $eventClass
     * @param string $listenerClass
     * @return void
     * @throws \BadMethodCallException|\Throwable
     */
    public function subscribe(string $eventClass, string $listenerClass){
        $this->dispatcher->subscribe(
            $eventClass,
            function($event) use ($listenerClass, $eventClass){
                try{
                    $listener = $this->resolveListener($listenerClass);
                    if (method_exists($listener, 'handle')){
                        $listener->handle($event);
                    }else{
                        throw new \BadMethodCallException("Listener {$listenerClass} must have a handle method");
                    }
                }catch (\Throwable $e){
                    echo "Error executing listener {$listenerClass} for event {$eventClass}: " . $e->getMessage();
                }
            }
        );
    }

    protected function resolveListener(string $listenerClass){
        return new $listenerClass;
    }

    /**
     * Optional hook for additional setup logic.
     * Called after event listeners are registered.
     * @return void
     */
    protected function boot(){}
}