<?php

namespace App\Helper;

use App\Domain\Entity\Agenda;

class AgendaSerializer
{
    public static function serialize(array $agenda): string
    {
        return json_encode([
            'id_agenda' => $agenda['id_agenda'],
            'situacao_agenda_app' => $agenda['situacao_agenda_app'],
            'agenda_dth' => $agenda['agenda_dth'],
            'tipo_agenda' => $agenda['tipo_agenda'],
            'tipo_procedimento' => $agenda['tipo_procedimento'],
            'procedimento' => $agenda['procedimento'],
            'descricao' => $agenda['descricao'],
            'solicitacao_dth' => $agenda['solicitacao_dth'],
            'situacao' => $agenda['situacao'],
            'embarque_dth' => $agenda['embarque_dth'],
            'local_embarque' => $agenda['local_embarque'],
            'desativado' => $agenda['desativado'],
            'atualizacao_dth' => $agenda['atualizacao_dth'],
            'criacao_dth' => $agenda['criacao_dth'],
            'id_tipo_agenda_sub' => $agenda['id_tipo_agenda_sub']
        ]);
    }

    public static function deserialize(array $aAgenda): Agenda
    {
        $oAgenda = new Agenda();
        return $oAgenda->setIdAgenda($aAgenda['id_agenda'])
                    ->setSituacaoAgendaApp($aAgenda['situacao_agenda_app'])
                    ->setAgendaDth($aAgenda['agenda_dth'])
                    ->setTipoAgenda($aAgenda['tipo_agenda'])
                    ->setTipoProcedimento($aAgenda['tipo_procedimento'])
                    ->setProcedimento($aAgenda['procedimento'])
                    ->setDescricao($aAgenda['descricao'])
                    ->setSolicitacaoDth($aAgenda['solicitacao_dth'])
                    ->setSitucao($aAgenda['situacao'])
                    ->setEmbarqueDth($aAgenda['embarque_dth'])
                    ->setLocalEmbarque($aAgenda['local_embarque'])
                    ->setDesativado($aAgenda['desativado'])
                    ->setAtualizacaoDth($aAgenda['atualizacao_dth'])
                    ->setCriacaoDth($aAgenda['criacao_dth'])
                    ->setIdTipoAgendaSub($aAgenda['id_tipo_agenda_sub']);
    }
}