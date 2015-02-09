<?php

class TypesTableSeeder extends Seeder {

    public function run() {
        DB::table('types')->delete();
        $types = array(
            [
                'code' => 'patrimonial_PT',
                'name' => 'Patrimonial',
                'title' => 'Confirmação de Vistoria',
                'first_notes' => 'Para a regularização do sinistro ao abrigo da apólice em referência, solicita-se o envio dos documentos ou a execução dos'
                . 'trabalhos abaixo indicados, até a data limite referenciada, apartir da qual o processo será encerrado e enviado à Seguradora, com os elementos disponíveis',
                'last_notes' => 'Os elementos/documentos solicitados devem ser entregues até __/__/____.'
            ],
            [
                'code' => 'transportes_PT',
                'name' => 'Transportes',
                'title' => 'Auto de Vistoria/Solicitação de Elementos',
                'first_notes' => 'Nesta data foram solicitados os seguintes elementos/documentos e/ou a realização das operações a seguir assinaladas e'
                . 'indispensáveis à instrução deste processo',
                'last_notes' => 'Os elementos/documentos solicitados devem ser entregues num prazo máximo de ___ dias úteis.'
        ]);
        $fields = [
            ['value' => 'Tel', 'type_id' => 1],
            ['value' => 'Email', 'type_id' => 1],
            ['value' => 'Terceiros', 'type_id' => 1],
            ['value' => 'Morada', 'type_id' => 1],
            ['value' => 'Pessoas Contactadas', 'type_id' => 2]
        ];
        $checkboxes = [
            ['value' => 'NIF e NIB (comprovativo emitido pela instituição bancária) do Segurado e/ou Terceiro', 'type_id' => 1],
            ['value' => 'Caderneta Predial e/ou Titulo Construtivo de Propriedade Horizontal', 'type_id' => 1],
            ['value' => 'Certidão passada pelas autoridades (PSP/GNR/PJ)', 'type_id' => 1],
            ['value' => 'Quando efetuada, cópia da participação de denúncia à Autoridade competente', 'type_id' => 1],
            ['value' => 'Relatório dos Bombeiros', 'type_id' => 1],
            ['value' => 'Pesquisas de Avarias', 'type_id' => 1],
            ['value' => 'Orçamento de reparação e/ou substituição devidamente discriminados', 'type_id' => 1],
            ['value' => 'Reclamação com identificação dos bens e unitariamente valorizados', 'type_id' => 1],
            ['value' => 'IES do Exercicio fiscal de ____________________________________________', 'type_id' => 1],
            ['value' => 'Mapa de amotizações e reintegrações de ______________________________', 'type_id' => 1],
            ['value' => 'Inventário de existências a _________________________', 'type_id' => 1],
            ['value' => 'Reclamação de quem tenha direito sobre a mercadoria nos termos, prazos e formas legais', 'type_id' => 2],
            ['value' => 'Cópia original da guia de transporte ou declaração de expedição CMR destinada ao Transportador', 'type_id' => 2],
            ['value' => 'Cópia do Manifesto TIR e/ou lista de carga', 'type_id' => 2],
            ['value' => 'Fatura comercial ou documentação comprovativa do valor da mercadoria na origem', 'type_id' => 2],
            ['value' => 'Lista de embalagem da mercadoria sinistrada ou documento comprovativo da quantidade de volumes e pesos brutos', 'type_id' => 2],
            ['value' => 'Quando efetuada, cópia da participação de denuncia à autoridade competente', 'type_id' => 2],
            ['value' => 'Relatório do Motorista descrevendo todo o percurso da viagem e acontecimentos, causas que motivaram o sinistro, perdas e danos na mercadoria, tipo de mercadoria afetada e estimativa de prejuízos', 'type_id' => 2],
            ['value' => 'Informação sobre a existencia de seguro de mercadoria', 'type_id' => 2],
            ['value' => 'Em caso de indemnização, comprovativo NIB da entidade à qual deverá ser liquidado a eventual indemnização', 'type_id' => 2],
            ['value' => 'Documentos de conjunto transportador (trator + semirreboque)', 'type_id' => 2],
            ['value' => 'Registo de velocidade do tacógrafo (caso acidente)', 'type_id' => 2],
            ['value' => 'Carta de condução do motorista', 'type_id' => 2],
            ['value' => 'Certificado de temperaturas (transporte de frio)', 'type_id' => 2],
            ['value' => 'Registo de temperaturas (transporte de frio)', 'type_id' => 2],
            ['value' => 'Fatura de revisão/reparação do equipamento de frio após a ocorrência (transporte de frio)', 'type_id' => 2],
        ];
        Eloquent::unguard();
        foreach ($types as $type) {
            Type::create($type);
        }
        foreach ($fields as $f) {
            TypeField::create($f);
        }
        foreach ($checkboxes as $c) {
            CheckField::create($c);
        }
        Eloquent::reguard();
    }

}
