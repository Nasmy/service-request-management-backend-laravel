<?php

namespace App\Repositories;

use App\Interfaces\CrudModelRepository;
use App\Models\Document;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @phpstan-extends CrudModelRepository<Document>
 */
final class DocumentRepository extends CrudModelRepository
{
    /**
     * @var string
     * @phpstan-var class-string<Document>
     */
    protected string $className = Document::class;

    /**
     * @param array $params
     * @param Builder|null $query
     * @return LengthAwarePaginator|Collection
     */
    public function all(array $params, ?Builder $query = null)
    {
        $documents = parent::all($params, $query);
        foreach ($documents as $document) {
            $this->getDocumentJob($document);
        }
        return $documents;
    }

    /**
     * @param int $id
     * @return Document
     */
    public function findById(int $id): Document
    {
        $document = parent::findById($id);
        $this->getDocumentJob($document);
        return $document;
    }

    /**
     * @param array $collection
     * @param int|null $id
     * @return Document
     */
    public function createOrUpdate(array $collection = [], ?int $id = null): Document
    {
        $document = parent::createOrUpdate($collection, $id);
        $this->getDocumentJob($document);
        return $document;
    }

    /**
     * @param Document $document
     * @return void
     */
    private function getDocumentJob(Document $document)
    {
        if ($document->job != null)
            $document->job->get();
    }
}
