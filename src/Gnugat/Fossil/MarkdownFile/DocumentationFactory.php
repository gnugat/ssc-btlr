<?php

/*
 * This file is part of the Fossil project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Fossil\MarkdownFile;

use Gnugat\Fossil\ProjectType\Project;
use Gnugat\Fossil\ProjectType\Bundle;
use Twig_Environment;

/**
 * Creates the project's documentation files using skeleton files.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class DocumentationFactory
{
    /** @var Twig_Environment */
    private $twig;

    /**
     * @param Twig_Environment $twig
     */
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
        if ($project->type() === Bundle::TYPE && $skeleton->isInsideDocumentationDirectory()) {
            $pathPieces[] = 'Resources';
        }
        $pathPieces[] = str_replace('.twig', '', $skeleton->relative_pathname);
        $absolutePathname = implode('/', $pathPieces);

        $viewParameters = array('project' => $project);
        $content = $this->twig->render($skeleton->relative_pathname, $viewParameters);

        return new Documentation($absolutePathname, $content);
    }
}
