<?php

namespace Ornicar\MessageBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Ornicar\MessageBundle\Security\AuthorizerInterface;

class AuthorizationValidator extends ConstraintValidator
{
    /**
     * @var AuthorizerInterface
     */
    protected $authorizer;

    /**
     * Constructor
     *
     * @param AuthorizerInterface $authorizer
     */
    public function __construct(AuthorizerInterface $authorizer)
    {
        $this->authorizer = $authorizer;
    }

    /**
     * Indicates whether the constraint is valid
     *
     * @param object     $value
     * @param Constraint $constraint
     */
    public function isValid($value, Constraint $constraint)
    {
        $recipient = $value->getRecipient();
        if (!$recipient) {
            return true;
        }
        if (!$this->authorizer->canMessageParticipant($recipient)) {
            $this->setMessage($constraint->message);
            return false;
        }

        return true;
    }
}