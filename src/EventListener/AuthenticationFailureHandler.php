<?php


namespace App\EventListener;


use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as ContractsEventDispatcherInterface;

class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $response = new Response();
        if($exception->getPrevious() instanceof UsernameNotFoundException){
            $response->setStatusCode(422);
            $response->setContent(json_encode([
                "message" => "Invalid form",
                "data" => [],
                "errors"=>["email" => "Hey That user doesn't exist baba"]
            ]));
        }elseif ($exception->getPrevious() instanceof  BadCredentialsException){
            $response->setStatusCode(422);
            $response->setContent(json_encode([
                "message" => "Invalid form",
                "data" => [],
                "errors"=>["password" => "That password and username don't match!"]
            ]));
        }else{
            $response->setStatusCode(422);
            $response->setContent(json_encode([
                "message" => "Invalid form",
                "data" => [],
                "errors"=>["Unknown" => "Something went wrong dude."]
            ]));
        }
        $event = new AuthenticationFailureEvent(
            $exception,
            $response
        );

        if ($this->dispatcher instanceof ContractsEventDispatcherInterface) {
            $this->dispatcher->dispatch($event, Events::AUTHENTICATION_FAILURE);
        } else {
            $this->dispatcher->dispatch(Events::AUTHENTICATION_FAILURE, $event);
        }

        return $event->getResponse();
    }
}
