<?php

namespace App\Test\Controller;

use App\Entity\Equipment;
use App\Repository\EquipmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EquipmentControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/equipment/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Equipment::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Equipment index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'equipment[nom]' => 'Testing',
            'equipment[etat]' => 'Testing',
            'equipment[projectDeRecherche]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Equipment();
        $fixture->setNom('My Title');
        $fixture->setEtat('My Title');
        $fixture->setProjectDeRecherche('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Equipment');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Equipment();
        $fixture->setNom('Value');
        $fixture->setEtat('Value');
        $fixture->setProjectDeRecherche('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'equipment[nom]' => 'Something New',
            'equipment[etat]' => 'Something New',
            'equipment[projectDeRecherche]' => 'Something New',
        ]);

        self::assertResponseRedirects('/equipment/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getEtat());
        self::assertSame('Something New', $fixture[0]->getProjectDeRecherche());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Equipment();
        $fixture->setNom('Value');
        $fixture->setEtat('Value');
        $fixture->setProjectDeRecherche('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/equipment/');
        self::assertSame(0, $this->repository->count([]));
    }
}
