<?php

namespace App\Test\Controller;

use App\Entity\Partnership;
use App\Repository\PartnershipRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PartnershipControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private PartnershipRepository $repository;
    private string $path = '/partnership/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Partnership::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Partnership index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'partnership[name]' => 'Testing',
            'partnership[text]' => 'Testing',
            'partnership[linkToWebsite]' => 'Testing',
            'partnership[file]' => 'Testing',
        ]);

        self::assertResponseRedirects('/partnership/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Partnership();
        $fixture->setName('My Title');
        $fixture->setText('My Title');
        $fixture->setLinkToWebsite('My Title');
        $fixture->setFile('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Partnership');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Partnership();
        $fixture->setName('My Title');
        $fixture->setText('My Title');
        $fixture->setLinkToWebsite('My Title');
        $fixture->setFile('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'partnership[name]' => 'Something New',
            'partnership[text]' => 'Something New',
            'partnership[linkToWebsite]' => 'Something New',
            'partnership[file]' => 'Something New',
        ]);

        self::assertResponseRedirects('/partnership/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getText());
        self::assertSame('Something New', $fixture[0]->getLinkToWebsite());
        self::assertSame('Something New', $fixture[0]->getFile());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Partnership();
        $fixture->setName('My Title');
        $fixture->setText('My Title');
        $fixture->setLinkToWebsite('My Title');
        $fixture->setFile('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/partnership/');
    }
}
