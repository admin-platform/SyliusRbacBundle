<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\RbacBundle\Form\Type;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceChoiceType;
use Sylius\Component\Rbac\Model\Role;
use Symfony\Component\Form\AbstractType;

/**
 * @author Arnaud Langlade <arn0d.dev@gmail.com>
 */
final class RoleEntityTypeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(Role::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\RbacBundle\Form\Type\RoleEntityType');
    }

    function it_is_a_form()
    {
        $this->shouldHaveType(AbstractType::class);
    }
}
