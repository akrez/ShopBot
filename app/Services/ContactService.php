<?php

namespace App\Services;

use App\Contracts\PortContract;
use App\DTO\ContactDTO;
use App\Models\Blog;
use App\Models\Contact;
use App\Support\ResponseBuilder;

class ContactService implements PortContract
{
    public function getLatestBlogContactsQuery(Blog $blog)
    {
        return $blog->contacts()->orderDefault();
    }

    public function findOrFailActiveBlogContact($contactId)
    {
        $blog = resolve(BlogService::class)->findOrFailActiveBlog();
        $contact = $blog->contacts()->where('id', $contactId)->first();
        abort_unless($contact, 404);

        return $contact;
    }

    public function firstContactById(Blog $blog, $id): ?Contact
    {
        if (strlen($id)) {
            return $blog->contacts()->where('id', $id)->first();
        }

        return null;
    }

    public function store(Blog $blog, ContactDTO $contactDTO)
    {
        $responseBuilder = resolve(ResponseBuilder::class)->input($contactDTO);

        $validation = $contactDTO->validate(true, ['blog' => $blog]);

        if ($validation->errors()->isNotEmpty()) {
            return $responseBuilder->status(422)->message('Unprocessable Entity')->errors($validation->errors());
        }

        $contact = $blog->contacts()->create($contactDTO->data());

        if (! $contact) {
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        return $responseBuilder->status(201)->data($contact)->message(__(':name is created successfully', [
            'name' => __('Contact'),
        ]));
    }

    public function update(Blog $blog, Contact $contact, ContactDTO $contactDTO)
    {
        $responseBuilder = resolve(ResponseBuilder::class)->input($contactDTO);

        $validation = $contactDTO->validate(false, ['blog' => $blog, 'id' => $contact->id]);

        if ($validation->errors()->isNotEmpty()) {
            return $responseBuilder->status(422)->message('Unprocessable Entity')->errors($validation->errors());
        }

        $isSuccessful = $contact->update($contactDTO->data());
        if (! $isSuccessful) {
            return $responseBuilder->status(500)->message('Internal Server Error');
        }

        return $responseBuilder->data($contact)->status(200)->message(__(':name is updated successfully', [
            'name' => __('Contact'),
        ]));
    }

    public function destroy(Blog $blog, Contact $contact)
    {
        if (! $contact->delete()) {
            return resolve(ResponseBuilder::class)->status(500)->message('Internal Server Error');
        }

        return resolve(ResponseBuilder::class)->status(200)->message(__(':name is deleted successfully', [
            'name' => __('Contact'),
        ]));
    }

    public function exportToExcel(Blog $blog)
    {
        $source = [];

        $source[] = [
            __('validation.attributes.id'),
            __('validation.attributes.contact_type'),
            __('validation.attributes.contact_key'),
            __('validation.attributes.contact_value'),
            __('validation.attributes.contact_link'),
            __('validation.attributes.contact_order'),
        ];

        foreach ($this->getLatestBlogContactsQuery($blog)->get() as $contact) {
            $source[] = [
                $contact->id,
                $contact->contact_type?->value,
                $contact->contact_key,
                $contact->contact_value,
                $contact->contact_link,
                $contact->contact_order,
            ];
        }

        return $source;
    }

    /**
     * @return array<int, ResponseBuilder>
     */
    public function importFromExcel(Blog $blog, array $rows): array
    {
        $result = [];
        //
        $skipedRow = 0;
        foreach ($rows as $row) {
            if ($skipedRow < 1) {
                $skipedRow++;

                continue;
            }
            //
            $row = ((array) $row) + array_fill(0, 6, null);
            $id = $row[0];
            //
            $contactDTO = new ContactDTO(
                $row[1],
                $row[2],
                $row[3],
                $row[4],
                $row[5]
            );
            //
            $contact = $this->firstContactById($blog, $id);
            //
            if ($contact) {
                $result[] = $this->update($blog, $contact, $contactDTO);
            } else {
                $result[] = $this->store($blog, $contactDTO);
            }
        }

        return $result;
    }
}
