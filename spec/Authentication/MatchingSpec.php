<?php

namespace spec\Http\Message\Authentication;

use Http\Message\Authentication;
use Psr\Http\Message\RequestInterface;
use PhpSpec\ObjectBehavior;

class MatchingSpec extends ObjectBehavior
{
    use AuthenticationBehavior;

    private $matcher;

    function let(Authentication $authentication)
    {
        $this->matcher = function($request) { return true; };

        $this->beConstructedWith($authentication, $this->matcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Http\Message\Authentication\Matching');
    }

    function it_has_an_authentication(Authentication $authentication)
    {
        $this->getAuthentication()->shouldReturn($authentication);
    }

    function it_has_a_matcher()
    {
        $this->getMatcher()->shouldReturn($this->matcher);
    }

    function it_authenticates_a_request(Authentication $authentication, RequestInterface $request, RequestInterface $newRequest)
    {
        $authentication->authenticate($request)->willReturn($newRequest);

        $this->authenticate($request)->shouldReturn($newRequest);
    }

    function it_does_not_authenticate_a_request(Authentication $authentication, RequestInterface $request)
    {
        $matcher = function($request) { return false; };

        $this->beConstructedWith($authentication, $matcher);

        $authentication->authenticate($request)->shouldNotBeCalled();

        $this->authenticate($request)->shouldReturn($request);
    }

    function it_creates_a_matcher_from_url(Authentication $authentication)
    {
        $this->createUrlMatcher($authentication, 'url')->shouldHaveType('Http\Message\Authentication\Matching');
    }
}
