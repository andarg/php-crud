<?php

namespace CRUDDemo;

use OLOG\BT\BT;
use OLOG\CRUD\CRUDCreateFormScript;
use OLOG\CRUD\CRUDTable;
use OLOG\CRUD\CRUDFormRow;
use OLOG\CRUD\CRUDTableColumn;
use OLOG\CRUD\CRUDTableFilterEqualOptions;
use OLOG\CRUD\CRUDTableFilterLike;
use OLOG\CRUD\CRUDTableWidgetDelete;
use OLOG\CRUD\CRUDTableWidgetHtml;
use OLOG\CRUD\CRUDTableWidgetHtmlWithLink;
use OLOG\CRUD\CRUDTableWidgetText;
use OLOG\CRUD\CRUDTableWidgetTextWithLink;
use OLOG\CRUD\CRUDFormWidgetInput;
use OLOG\CRUD\CRUDTableWidgetWeight;

class DemoNodesListAction
{
	static public function getUrl()
	{
		return '/nodes';
	}

	public function action()
	{
		\OLOG\Exits::exit403If(!CRUDDemoAuth::currentUserHasAnyOfPermissions([1]));

		$table_id = 'tableContainer_NodeList';
		$form_id = 'formElem_NodeList';

		$html = '';

		$html .= CRUDTable::html(
			DemoNode::class,
			\OLOG\CRUD\CRUDForm::html(
				new DemoNode(),
				[
					new CRUDFormRow(
						'Title',
						new CRUDFormWidgetInput('title')
					),
					new CRUDFormRow(
						'body2',
						new CRUDFormWidgetInput('body2')
					)
				],
				'',
				[],
				$form_id
			),
			[
				new CRUDTableColumn(
					'Title',
					new CRUDTableWidgetHtmlWithLink('{this->title}<br>{this->getReverseTitle()}', DemoNodeEditAction::getUrl('{this->id}'))
				),
				new CRUDTableColumn(
					'Reverse title',
					new CRUDTableWidgetText('{this->getReverseTitle()}')
				),
                new CRUDTableColumn(
                    '',
                    new CRUDTableWidgetWeight([])
                ),
                new CRUDTableColumn(
                    '',
                    new CRUDTableWidgetDelete()
                ),
			],
			[
			    new CRUDTableFilterLike('h7g98347hg934', 'Название', 'title'),
                new CRUDTableFilterEqualOptions('hk4g78gwed', 'Опубликовано', 'is_published', [0 => 'Нет', 1 => 'Да'], false, 0, false)
            ],
			'weight',
			$table_id,
            CRUDTable::FILTERS_POSITION_TOP
		);

		// Загрузка скриптов
		$html .= CRUDCreateFormScript::getHtml($form_id, $table_id);

		DemoLayoutTemplate::render($html, 'Nodes', self::getBreadcrumbsArr());
	}

	static public function getBreadcrumbsArr()
	{
		return array_merge(DemoMainPageAction::breadcrumbsArr(), [BT::a(self::getUrl(), 'Nodes')]);
	}
}