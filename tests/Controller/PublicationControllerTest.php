<?php

namespace App\Test\Controller;

use App\Entity\Publication;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PublicationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/publication/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Publication::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Publication index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'publication[titre]' => 'Testing',
            'publication[auteurs]' => 'Testing',
            'publication[motsCles]' => 'Testing',
            'publication[projectDeRecherche]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Publication();
        $fixture->setTitre('My Title');
        $fixture->setAuteurs('My Title');
        $fixture->setMotsCles('My Title');
        $fixture->setProjectDeRecherche('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Publication');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Publication();
        $fixture->setTitre('Value');
        $fixture->setAuteurs('Value');
        $fixture->setMotsCles('Value');
        $fixture->setProjectDeRecherche('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'publication[titre]' => 'Something New',
            'publication[auteurs]' => 'Something New',
            'publication[motsCles]' => 'Something New',
            'publication[projectDeRecherche]' => 'Something New',
        ]);

        self::assertResponseRedirects('/publication/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitre());
        self::assertSame('Something New', $fixture[0]->getAuteurs());
        self::assertSame('Something New', $fixture[0]->getMotsCles());
        self::assertSame('Something New', $fixture[0]->getProjectDeRecherche());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Publication();
        $fixture->setTitre('Value');
        $fixture->setAuteurs('Value');
        $fixture->setMotsCles('Value');
        $fixture->setProjectDeRecherche('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/publication/');
        self::assertSame(0, $this->repository->count([]));
    }
}
