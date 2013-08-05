<?php
namespace Application\View\Helper;

use Zend\View\Helper\Placeholder\Container\AbstractStandalone;
use Zend\I18n\Translator\TranslatorAwareInterface;
use Zend\View\Helper\Placeholder\Container\AbstractContainer;
use Zend\View\Exception\DomainException;
use Zend\I18n\Translator\Translator;

class Breadcrum extends AbstractStandalone implements TranslatorAwareInterface
{

    /**
     * Registry key for placeholder
     *
     * @var string
     */
    protected $regKey = 'Zend_View_Helper_HeadTitle';

    /**
     * Default title rendering order (i.e.
     * order in which each title attached)
     *
     * @var string
     */
    protected $defaultAttachOrder = null;

    /**
     * Translator (optional)
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Translator text domain (optional)
     *
     * @var string
     */
    protected $translatorTextDomain = 'default';

    /**
     * Whether translator should be used
     *
     * @var bool
     */
    protected $translatorEnabled = true;

    /**
     * Retrieve placeholder for title element and optionally set state
     *
     * @param string $title            
     * @param string $setType            
     * @return HeadTitle
     */
    public function __invoke($item = null, $setType = null)
    {
        if (null === $setType) {
            $setType = (null === $this->getDefaultAttachOrder()) ? AbstractContainer::APPEND : $this->getDefaultAttachOrder();
        }
        
        if ($item != array()) {
            if ($setType == AbstractContainer::SET) {
                $this->set($item);
            } elseif ($setType == AbstractContainer::PREPEND) {
                $this->prepend($item);
            } else {
                $this->append($item);
            }
        }
        
        return $this;
    }

    /**
     * Render title (wrapped by title tag)
     *
     * @param string|null $indent            
     * @return string
     */
    public function toString($indent = null)
    {
        $indent = (null !== $indent) ? $this->getWhitespace($indent) : $this->getIndent();
        
        $output = $this->renderTitle();
        
        foreach ($this->breadcrumb as $key => $item) {
            echo '<li class="' . isset($item['class']) ? $item['class'] : '' . '">';
            if ($key < count($this->breadcrumb) - 1) {
                echo '<a href="' . isset($item['url']) ? $item['url'] : '' . '">' . isset($item['content']) ? $item['content'] : '' . '</a>';
            } else {
                echo '<a>' . isset($item['content']) ? $item['content'] : '' . '</a>';
            }
            echo '</li>';
            if ($key < count($this->breadcrumb) - 1) {
                echo '<li class="separator"></li>';
            }
        }
        
        return $indent . '<title>' . $output . '</title>';
    }

    /**
     * Render title string
     *
     * @return string
     */
    public function renderTitle()
    {
        $items = array();
        
        if (null !== ($translator = $this->getTranslator())) {
            foreach ($this as $item) {
                $items[] = $translator->translate($item, $this->getTranslatorTextDomain());
            }
        } else {
            foreach ($this as $item) {
                $items[] = $item;
            }
        }
        
        $separator = $this->getSeparator();
        $output = '';
        
        $prefix = $this->getPrefix();
        if ($prefix) {
            $output .= $prefix;
        }
        
        $output .= implode($separator, $items);
        
        $postfix = $this->getPostfix();
        if ($postfix) {
            $output .= $postfix;
        }
        
        $output = ($this->autoEscape) ? $this->escape($output) : $output;
        
        return $output;
    }

    /**
     * Set a default order to add titles
     *
     * @param string $setType            
     * @throws Exception\DomainException
     * @return HeadTitle
     */
    public function setDefaultAttachOrder($setType)
    {
        if (! in_array($setType, array(
            AbstractContainer::APPEND,
            AbstractContainer::SET,
            AbstractContainer::PREPEND
        ))) {
            throw new DomainException("You must use a valid attach order: 'PREPEND', 'APPEND' or 'SET'");
        }
        $this->defaultAttachOrder = $setType;
        
        return $this;
    }

    /**
     * Get the default attach order, if any.
     *
     * @return mixed
     */
    public function getDefaultAttachOrder()
    {
        return $this->defaultAttachOrder;
    }
    
    // Translator methods - Good candidate to refactor as a trait with PHP 5.4
    
    /**
     * Sets translator to use in helper
     *
     * @param Translator $translator
     *            [optional] translator.
     *            Default is null, which sets no translator.
     * @param string $textDomain
     *            [optional] text domain
     *            Default is null, which skips setTranslatorTextDomain
     * @return HeadTitle
     */
    public function setTranslator(Translator $translator = null, $textDomain = null)
    {
        $this->translator = $translator;
        if (null !== $textDomain) {
            $this->setTranslatorTextDomain($textDomain);
        }
        return $this;
    }

    /**
     * Returns translator used in helper
     *
     * @return Translator null
     */
    public function getTranslator()
    {
        if (! $this->isTranslatorEnabled()) {
            return null;
        }
        
        return $this->translator;
    }

    /**
     * Checks if the helper has a translator
     *
     * @return bool
     */
    public function hasTranslator()
    {
        return (bool) $this->getTranslator();
    }

    /**
     * Sets whether translator is enabled and should be used
     *
     * @param bool $enabled
     *            [optional] whether translator should be used.
     *            Default is true.
     * @return HeadTitle
     */
    public function setTranslatorEnabled($enabled = true)
    {
        $this->translatorEnabled = (bool) $enabled;
        return $this;
    }

    /**
     * Returns whether translator is enabled and should be used
     *
     * @return bool
     */
    public function isTranslatorEnabled()
    {
        return $this->translatorEnabled;
    }

    /**
     * Set translation text domain
     *
     * @param string $textDomain            
     * @return HeadTitle
     */
    public function setTranslatorTextDomain($textDomain = 'default')
    {
        $this->translatorTextDomain = $textDomain;
        return $this;
    }

    /**
     * Return the translation text domain
     *
     * @return string
     */
    public function getTranslatorTextDomain()
    {
        return $this->translatorTextDomain;
    }
}