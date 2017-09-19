<?php

namespace Hgabka\KunstmaanSettingsBundle\Security;

use Hgabka\KunstmaanSettingsBundle\Entity\Setting;
use Kunstmaan\AdminBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SettingVoter extends Voter
{
    const EDIT = 'edit';

    /** @var string */
    protected $editorRole;

    /** @var AccessDecisionManagerInterface */
    protected $decisionManager;

    /**
     * BannerVoter constructor.
     *
     * @param string $editorRole
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager, $editorRole)
    {
        $this->decisionManager = $decisionManager;
        $this->editorRole = $editorRole;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT], true)) {
            return false;
        }

        if (!$subject instanceof Setting) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($subject, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(Banner $banner, TokenInterface $token)
    {
        return $this->decisionManager->decide($token, [$this->editorRole]);
    }
}
