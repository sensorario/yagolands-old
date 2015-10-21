<?php

/* base.twig */
class __TwigTemplate_1872a6484870e6e3fa81b121da35fa8e0408116be547083947d855ee894a273c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
    <head>
        <title>";
        // line 3
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : null), "html", null, true);
        echo "</title>
    </head>
    <body>
        ";
        // line 6
        echo twig_escape_filter($this->env, (isset($context["content"]) ? $context["content"] : null), "html", null, true);
        echo "
    </body>
</html>
";
    }

    public function getTemplateName()
    {
        return "base.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  29 => 6,  23 => 3,  19 => 1,);
    }
}
/* <!DOCTYPE html>*/
/*     <head>*/
/*         <title>{{title}}</title>*/
/*     </head>*/
/*     <body>*/
/*         {{content}}*/
/*     </body>*/
/* </html>*/
/* */
