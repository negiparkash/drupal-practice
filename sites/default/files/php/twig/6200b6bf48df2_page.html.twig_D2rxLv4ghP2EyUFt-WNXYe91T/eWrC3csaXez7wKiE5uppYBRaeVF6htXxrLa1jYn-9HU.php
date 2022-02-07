<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* themes/landing_theme/templates/page.html.twig */
class __TwigTemplate_dba3823dd53b715dcdd60c4615003d79fe5ab36dc1cf7f727ee95409e4a24e63 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo " <div class=\"preloader\">
        <div class=\"preloader-inner\">
            <div class=\"preloader-icon\">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- /End Preloader -->

    <!-- Start Header Area -->
    <header class=\"header navbar-area\">
        <div class=\"container\">
            <div class=\"row align-items-center\">
                <div class=\"col-lg-12\">
                    <div class=\"nav-inner\">
                        <!-- Start Navbar -->
                        <nav class=\"navbar navbar-expand-lg\">
                        ";
        // line 20
        echo "                            ";
        if (twig_test_empty(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 20))) {
            // line 21
            echo "                            <a class=\"navbar-brand\" href=\"index.html\">
                                <img src=\"";
            // line 22
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["directory"] ?? null), 22, $this->source), "html", null, true);
            echo "/images/logo/bb.png\" alt=\"Logo\"> 
                            ";
        } else {
            // line 24
            echo "                               ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 24), 24, $this->source), "html", null, true);
            echo "

                                ";
        }
        // line 26
        echo "  
                            <div class=\"collapse navbar-collapse sub-menu-bar\" id=\"navbarSupportedContent\">
                                ";
        // line 28
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "menu", [], "any", false, false, true, 28), 28, $this->source), "html", null, true);
        echo "
                            </div> <!-- navbar collapse -->
                            <div class=\"button add-list-button\">
                                <a href=\"javascript:void(0)\" class=\"btn\">Get it now</a>
                            </div>
                        </nav>
                        <!-- End Navbar -->
                    </div>
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </header>
    <!-- End Header Area -->

    <!-- Start Hero Area -->
    <section id=\"home\" class=\"hero-area\">
        <div class=\"container\">
            <div class=\"row align-items-center\">
                <div class=\"col-lg-5 col-md-12 col-12\">
                    <div class=\"hero-content\">
                    <h2>";
        // line 48
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "banner_text", [], "any", false, false, true, 48), 48, $this->source), "html", null, true);
        echo "</h2>
                    </div>
                </div>
                <div class=\"col-lg-7 col-md-12 col-12\">
                    <div class=\"hero-image wow fadeInRight\" data-wow-delay=\".4s\">
                        ";
        // line 53
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "banner", [], "any", false, false, true, 53), 53, $this->source), "html", null, true);
        echo "
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Hero Area -->
    <!-- Start Features Area -->
    <section id=\"features\" class=\"features section\">
        <div class=\"container\">
         <div class=\"row\">
                <div class=\"col-12\">  
                ";
        // line 65
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "highlight_one", [], "any", false, false, true, 65), 65, $this->source), "html", null, true);
        echo "
                    </div>
                </div>
            </div>
            <div class=\"row\">
            ";
        // line 70
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "content", [], "any", false, false, true, 70), 70, $this->source), "html", null, true);
        echo "
        </div>
        </div>
    </section>
    <!-- End Features Area -->
          ";
        // line 87
        echo "

    <!-- Start Achievement Area -->
    <section class=\"our-achievement section\">
        <div class=\"container \" >
         
                        ";
        // line 93
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "block", [], "any", false, false, true, 93), 93, $this->source), "html", null, true);
        echo "
                
        </div>
    </section>
    <!-- End Achievement Area -->

    <!-- Start Pricing Table Area -->
    <section id=\"pricing\" class=\"pricing-table section\">
        <div class=\"container\">
            <div class=\"row\">
                ";
        // line 103
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "highlight_two", [], "any", false, false, true, 103), 103, $this->source), "html", null, true);
        echo "
                </div>
            </div>
            <div class=\"row\">
             ";
        // line 107
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "package", [], "any", false, false, true, 107), 107, $this->source), "html", null, true);
        echo "   
            </div>
        </div>
    </section>
    <!--/ End Pricing Table Area -->
    <!-- Start Call To Action Area -->
    <section class=\"section call-action\">
        <div class=\"container\">
            <div class=\"row\">
            ";
        // line 116
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "highlight_three", [], "any", false, false, true, 116), 116, $this->source), "html", null, true);
        echo "               
            </div>
        </div>
    </section>
    <!-- End Call To Action Area -->
    <!-- Start Footer Area -->
    <footer class=\"footer\">
        <!-- Start Footer Top -->
        <div class=\"footer-top\">
            <div class=\"container\">
                <div class=\"row\">
                    <div class=\"col-lg-4 col-md-4 col-12\">
                        <!-- Single Widget -->
                        <div class=\"single-footer f-about\">
                           ";
        // line 130
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_column_1", [], "any", false, false, true, 130), 130, $this->source), "html", null, true);
        echo "
                        </div>
                        <!-- End Single Widget -->
                    </div>
                    <div class=\"col-lg-8 col-md-8 col-12\">
                        <div class=\"row\">
                            <div class=\"col-lg-3 col-md-6 col-12\">
                              ";
        // line 137
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_column_2", [], "any", false, false, true, 137), 137, $this->source), "html", null, true);
        echo "
                            </div>
                            <div class=\"col-lg-3 col-md-6 col-12\">
                                ";
        // line 140
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_column_3", [], "any", false, false, true, 140), 140, $this->source), "html", null, true);
        echo "
                            </div>
                            <div class=\"col-lg-3 col-md-6 col-12\">
                                ";
        // line 143
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_column_4", [], "any", false, false, true, 143), 143, $this->source), "html", null, true);
        echo "
                            </div>
                            <div class=\"col-lg-3 col-md-6 col-12\">
                            ";
        // line 146
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "footer_column_5", [], "any", false, false, true, 146), 146, $this->source), "html", null, true);
        echo "
                        </div>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Footer Top -->
    </footer>
    <!--/ End Footer Area -->

    <!-- ========================= scroll-top ========================= -->
    <a href=\"#\" class=\"scroll-top\">
        <i class=\"lni lni-chevron-up\"></i>
    </a>

    <!-- ========================= JS here ========================= -->
    
";
    }

    public function getTemplateName()
    {
        return "themes/landing_theme/templates/page.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  228 => 146,  222 => 143,  216 => 140,  210 => 137,  200 => 130,  183 => 116,  171 => 107,  164 => 103,  151 => 93,  143 => 87,  135 => 70,  127 => 65,  112 => 53,  104 => 48,  81 => 28,  77 => 26,  70 => 24,  65 => 22,  62 => 21,  59 => 20,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source(" <div class=\"preloader\">
        <div class=\"preloader-inner\">
            <div class=\"preloader-icon\">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- /End Preloader -->

    <!-- Start Header Area -->
    <header class=\"header navbar-area\">
        <div class=\"container\">
            <div class=\"row align-items-center\">
                <div class=\"col-lg-12\">
                    <div class=\"nav-inner\">
                        <!-- Start Navbar -->
                        <nav class=\"navbar navbar-expand-lg\">
                        {# {{page.header}} #}
                            {% if page.header is empty %}
                            <a class=\"navbar-brand\" href=\"index.html\">
                                <img src=\"{{directory}}/images/logo/bb.png\" alt=\"Logo\"> 
                            {% else %}
                               {{page.header}}

                                {% endif %}  
                            <div class=\"collapse navbar-collapse sub-menu-bar\" id=\"navbarSupportedContent\">
                                {{page.menu}}
                            </div> <!-- navbar collapse -->
                            <div class=\"button add-list-button\">
                                <a href=\"javascript:void(0)\" class=\"btn\">Get it now</a>
                            </div>
                        </nav>
                        <!-- End Navbar -->
                    </div>
                </div>
            </div> <!-- row -->
        </div> <!-- container -->
    </header>
    <!-- End Header Area -->

    <!-- Start Hero Area -->
    <section id=\"home\" class=\"hero-area\">
        <div class=\"container\">
            <div class=\"row align-items-center\">
                <div class=\"col-lg-5 col-md-12 col-12\">
                    <div class=\"hero-content\">
                    <h2>{{ page.banner_text }}</h2>
                    </div>
                </div>
                <div class=\"col-lg-7 col-md-12 col-12\">
                    <div class=\"hero-image wow fadeInRight\" data-wow-delay=\".4s\">
                        {{page.banner}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Hero Area -->
    <!-- Start Features Area -->
    <section id=\"features\" class=\"features section\">
        <div class=\"container\">
         <div class=\"row\">
                <div class=\"col-12\">  
                {{  page.highlight_one}}
                    </div>
                </div>
            </div>
            <div class=\"row\">
            {{page.content}}
        </div>
        </div>
    </section>
    <!-- End Features Area -->
          {# <section id=\"features\" class=\"features section\">
          <div class=\"container\">
              <div class=\"row\">
                  <div class=\"col-12\">
                  {{  page.highlight_one}}
                  </div>
              </div>
              <div class=\"row\">
                  {{page.services}}
              </div>
          </div>
      </section> #}


    <!-- Start Achievement Area -->
    <section class=\"our-achievement section\">
        <div class=\"container \" >
         
                        {{page.block}}
                
        </div>
    </section>
    <!-- End Achievement Area -->

    <!-- Start Pricing Table Area -->
    <section id=\"pricing\" class=\"pricing-table section\">
        <div class=\"container\">
            <div class=\"row\">
                {{page.highlight_two}}
                </div>
            </div>
            <div class=\"row\">
             {{page.package}}   
            </div>
        </div>
    </section>
    <!--/ End Pricing Table Area -->
    <!-- Start Call To Action Area -->
    <section class=\"section call-action\">
        <div class=\"container\">
            <div class=\"row\">
            {{page.highlight_three}}               
            </div>
        </div>
    </section>
    <!-- End Call To Action Area -->
    <!-- Start Footer Area -->
    <footer class=\"footer\">
        <!-- Start Footer Top -->
        <div class=\"footer-top\">
            <div class=\"container\">
                <div class=\"row\">
                    <div class=\"col-lg-4 col-md-4 col-12\">
                        <!-- Single Widget -->
                        <div class=\"single-footer f-about\">
                           {{page.footer_column_1}}
                        </div>
                        <!-- End Single Widget -->
                    </div>
                    <div class=\"col-lg-8 col-md-8 col-12\">
                        <div class=\"row\">
                            <div class=\"col-lg-3 col-md-6 col-12\">
                              {{page.footer_column_2}}
                            </div>
                            <div class=\"col-lg-3 col-md-6 col-12\">
                                {{page.footer_column_3}}
                            </div>
                            <div class=\"col-lg-3 col-md-6 col-12\">
                                {{page.footer_column_4}}
                            </div>
                            <div class=\"col-lg-3 col-md-6 col-12\">
                            {{page.footer_column_5}}
                        </div>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ End Footer Top -->
    </footer>
    <!--/ End Footer Area -->

    <!-- ========================= scroll-top ========================= -->
    <a href=\"#\" class=\"scroll-top\">
        <i class=\"lni lni-chevron-up\"></i>
    </a>

    <!-- ========================= JS here ========================= -->
    
", "themes/landing_theme/templates/page.html.twig", "C:\\xampp\\htdocs\\drupal-9.3.2\\drupal-9.3.2\\themes\\landing_theme\\templates\\page.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 20);
        static $filters = array("escape" => 22);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['escape'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
