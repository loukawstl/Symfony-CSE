<?php

namespace App\Test\Controller;

use App\Entity\StaticContent;
use App\Repository\StaticContentRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StaticContentControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private StaticContentRepository $repository;
    private string $path = '/static/content/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(StaticContent::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('StaticContent index');

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
            'static_content[section]' => 'Testing',
            'static_content[page]' => 'Testing',
            'static_content[content]' => 'Testing',
        ]);

        self::assertResponseRedirects('/static/content/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new StaticContent();
        $fixture->setSection('My Title');
        $fixture->setPage('My Title');
        $fixture->setContent('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('StaticContent');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new StaticContent();
        $fixture->setSection('My Title');
        $fixture->setPage('My Title');
        $fixture->setContent('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'static_content[section]' => 'Something New',
            'static_content[page]' => 'Something New',
            'static_content[content]' => 'Something New',
        ]);

        self::assertResponseRedirects('/static/content/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getSection());
        self::assertSame('Something New', $fixture[0]->getPage());
        self::assertSame('Something New', $fixture[0]->getContent());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new StaticContent();
        $fixture->setSection('My Title');
        $fixture->setPage('My Title');
        $fixture->setContent('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/static/content/');
    }
}
