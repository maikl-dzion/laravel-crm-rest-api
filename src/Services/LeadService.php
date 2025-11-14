<?php

namespace VentureDrake\LaravelCrm\Services;

use Ramsey\Uuid\Uuid;
use VentureDrake\LaravelCrm\Models\Lead;
use VentureDrake\LaravelCrm\Models\PipelineStage;
use VentureDrake\LaravelCrm\Repositories\LeadRepository;

class LeadService
{
    /**
     * @var LeadRepository
     */
    private $leadRepository;

    /**
     * LeadService constructor.
     * @param LeadRepository $leadRepository
     */
    public function __construct(LeadRepository $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    public function create($request, $person = null, $organisation = null, $client = null)
    {

        $lead = Lead::create([
            'external_id'     => Uuid::uuid4()->toString(),
            'person_id'       => $person->id ?? null,
            'organisation_id' => $organisation->id ?? null,
            'client_id'       => $client->id ?? null,
            'title'           => $request->title ?? '' ,
            'description'     => $request->description ?? null,
            'amount'          => $request->amount ?? 0,
            'currency'        => $request->currency ?? 'RUB',
            'lead_source_id'  => $request->lead_source_id ?? null,

            'call_back'  => $request->call_back ?? null,
            'will_come'  => $request->will_come ?? null,

            'lead_status_id'  => 1,
            'user_owner_id'   => $request->user_owner_id,
            'pipeline_id'     => PipelineStage::find($request->pipeline_stage_id)->pipeline->id ?? null,
            'pipeline_stage_id' => $request->pipeline_stage_id ?? null,
        ]);

        $lead->labels()->sync($request->labels ?? []);

        return $lead;
    }

    public function update($request, Lead $lead, $person = null, $organisation = null, $client = null)
    {
        if(empty($request->title)) {
            $request->title = $lead->title;
        }

        $lead->update([
            'person_id' => $person->id ?? null,
            'organisation_id' => $organisation->id ?? null,
            'client_id' => $client->id ?? null,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount ?? 0,
            'currency' => $request->currency ?? 'RUB',

            'call_back'  => $request->call_back ?? null,
            'will_come'  => $request->will_come ?? null,

            'user_owner_id' => $request->user_owner_id,
            'pipeline_id' => PipelineStage::find($request->pipeline_stage_id)->pipeline->id ?? null,
            'pipeline_stage_id' => $request->pipeline_stage_id ?? null,

            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $lead->labels()->sync($request->labels ?? []);

        return $lead;
    }
}
