<?php

namespace Gnugat\Fossil\Factory;

use Gnugat\Fossil\Model\Documentation;
use Gnugat\Fossil\Model\Project;
use Gnugat\Fossil\Model\Skeleton;
use Twig_Environment;

class DocumentationFactory
{
    /** @var Twig_Environment */
    private $twig;

    /** @param Twig_Environment $twig */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param Skeleton $skeleton
     * @param Project  $project
     *
     * @return Documentation
     */
    public function make(Skeleton $skeleton, Project $project)
    {
        $pathPieces[] = $project->path;
        if ($project->is_bundle() && $skeleton->isInsideDocumentationDirectory()) {
            $pathPieces[] = 'Resources';
        }
        $pathPieces[] = str_replace('.twig', '', $skeleton->relative_pathname);
        $absolutePathname = implode('/', $pathPieces);

        $viewParameters = array('project' => $project);
        $content = $this->twig->render($skeleton->relative_pathname, $viewParameters);

        return new Documentation($absolutePathname, $content);
    }
}
