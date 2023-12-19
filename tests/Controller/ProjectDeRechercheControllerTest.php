<?php

namespace App\Test\Controller;

use App\Entity\ProjectDeRecherche;
use App\Repository\ProjectDeRechercheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjectDeRechercheControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/project/de/recherche/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(ProjectDeRecherche::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('ProjectDeRecherche index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'project_de_recherche[titre]' => 'Testing',
            'project_de_recherche[description]' => 'Testing',
            'project_de_recherche[etatAvancement]' => 'Testing',
            'project_de_recherche[chercheur]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new ProjectDeRecherche();
        $fixture->setTitre('My Title');
        $fixture->setDescription('My Title');
        $fixture->setEtatAvancement('My Title');
        $fixture->setChercheur('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('ProjectDeRecherche');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new ProjectDeRecherche();
        $fixture->setTitre('Value');
        $fixture->setDescription('Value');
        $fixture->setEtatAvancement('Value');
        $fixture->setChercheur('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'project_de_recherche[titre]' => 'Something New',
            'project_de_recherche[description]' => 'Something New',
            'project_de_recherche[etatAvancement]' => 'Something New',
            'project_de_recherche[chercheur]' => 'Something New',
        ]);

        self::assertResponseRedirects('/project/de/recherche/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitre());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getEtatAvancement());
        self::assertSame('Something New', $fixture[0]->getChercheur());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new ProjectDeRecherche();
        $fixture->setTitre('Value');
        $fixture->setDescription('Value');
        $fixture->setEtatAvancement('Value');
        $fixture->setChercheur('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/project/de/recherche/');
        self::assertSame(0, $this->repository->count([]));
    }
}
