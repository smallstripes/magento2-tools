<?php
/**
 * Copyright © 2017 SmallStripes. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace SmallStripes\Tools\Console\Command;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\CatalogUrlRewrite\Model\CategoryUrlRewriteGenerator;
use Magento\CatalogUrlRewrite\Model\UrlRewriteBunchReplacer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UrlRecreateCommand
 */
class UrlRecreateCommand extends Command
{

    /**
     * Key for category_id
     */
    const CATEGORY_ID = 'category_id';

    /**
     * Key for store_id
     */
    const STORE_ID = 'store_id';

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * Category UrlRewrite generator.
     *
     * @var CategoryUrlRewriteGenerator
     */
    private $categoryUrlRewriteGenerator;
    /**
     * Url Rewrite Replacer based on bunches.
     *
     * @var UrlRewriteBunchReplacer
     */
    private $urlRewriteBunchReplacer;

    /**
     * UrlRecreateCommand constructor.
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CategoryUrlRewriteGenerator $categoryUrlRewriteGenerator
     * @param UrlRewriteBunchReplacer $urlRewriteBunchReplacer
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        CategoryUrlRewriteGenerator $categoryUrlRewriteGenerator,
        UrlRewriteBunchReplacer $urlRewriteBunchReplacer
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryUrlRewriteGenerator = $categoryUrlRewriteGenerator;
        $this->urlRewriteBunchReplacer = $urlRewriteBunchReplacer;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('smallstripes:urlrewrite:category');
        $this->setDescription('Recreates category url rewrites.');
        $this->setDefinition([
            new InputArgument(
                self::CATEGORY_ID,
                InputArgument::REQUIRED,
                'Category ID.'
            ),
            new InputArgument(
                self::STORE_ID,
                InputArgument::REQUIRED,
                'Store ID.'
            ),
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $languageId = $input->getArgument(self::CATEGORY_ID);
        $storeId = $input->getArgument(self::STORE_ID);
        $category = $this->categoryRepository->get(22, 0);
        $categoryUrlRewriteResult = $this->categoryUrlRewriteGenerator->generate($category);
        $this->urlRewriteBunchReplacer->doBunchReplace($categoryUrlRewriteResult);
    }
}
