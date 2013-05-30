<?php

namespace Msi\CmfBundle\Controller\Admin;

use Msi\CmfBundle\Controller\CoreController;
use Symfony\Component\HttpFoundation\Request;

class MenuNodeController extends CoreController
{
    public function sortAction(Request $request)
    {
        $node = $this->admin->getObjectManager()->getFindByQueryBuilder(
            ['a.id' => $request->query->get('current')]
        )->getQuery()->getOneOrNullResult();

        $currentRow = $request->query->get('currentRow');
        $nextRow = $request->query->get('nextRow');
        $prevRow = $request->query->get('prevRow');

        $newPos = $nextRow ?: $prevRow;

        // $nextRowParentId = $request->query->get('nextRowParentId');
        // $prevRowParentId = $request->query->get('prevRowParentId');

        // $newParentId = $nextRow ? $nextRowParentId : $prevRowParentId;

        // if ($newParentId != $node->getParent()->getId()) {
        //     $parent = $this->admin->getObjectManager()->getFindByQueryBuilder(
        //         ['a.id' => $newParentId]
        //     )->getQuery()->getOneOrNullResult();
        //     $node->setParent($parent);
        //     $this->admin->getObjectManager()->update($node);
        // }

        if ($newPos < $currentRow) {
            $this->admin->getObjectManager()->moveUp($node, $currentRow - $newPos);
        } else {
            $this->admin->getObjectManager()->moveDown($node, $newPos - $currentRow);
        }

        return $this->redirect($this->admin->genUrl('list'));
    }
}
