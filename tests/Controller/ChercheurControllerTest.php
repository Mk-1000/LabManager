<?php

namespace App\Test\Controller;

use App\Entity\Chercheur;
use App\Repository\ChercheurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChercheurControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/chercheur/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Chercheur::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Chercheur index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'chercheur[nom]' => 'Testing',
            'chercheur[prenom]' => 'Testing',
            'chercheur[email]' => 'Testing',
            'chercheur[motDePasse]' => 'Testing',
            'chercheur[role]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Chercheur();
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setEmail('My Title');
        $fixture->setMotDePasse('My Title');
        $fixture->setRole('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Chercheur');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Chercheur();
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setEmail('Value');
        $fixture->setMotDePasse('Value');
        $fixture->setRole('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'chercheur[nom]' => 'Something New',
            'chercheur[prenom]' => 'Something New',
            'chercheur[email]' => 'Something New',
            'chercheur[motDePasse]' => 'Something New',
            'chercheur[role]' => 'Something New',
        ]);

        self::assertResponseRedirects('/chercheur/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getMotDePasse());
        self::assertSame('Something New', $fixture[0]->getRole());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Chercheur();
        $fixture->setNom('Value');
        $fixture->setPrenom('Value');
        $fixture->setEmail('Value');
        $fixture->setMotDePasse('Value');
        $fixture->setRole('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/chercheur/');
        self::assertSame(0, $this->repository->count([]));
    }
}
