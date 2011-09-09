#!/bin/bash

if [[ ! -e deploy.conf ]]; then
	echo "No deploy.conf present. Writing defaults."

	CURRENTDIR=`pwd`
	SUGGEST_MODULE_NAME=`basename $CURRENTDIR`
	SUGGEST_MODULE_CODE_PATH=`echo $SUGGEST_MODULE_NAME | sed 's/_/\//g'`
	SUGGEST_MODULE_DESIGN_PATH=`echo $SUGGEST_MODULE_NAME | tr '[[A-Z]]' '[[a-z]]'`

	echo "DEPLOY_TARGET=" > deploy.conf
	echo "MAGENTO_DESIGN_PACKAGE=enterprise/default" >> deploy.conf
	echo "MAGENTO_SKIN_PACKAGE=enterprise/default" >> deploy.conf
	echo "MODULE_NAME=$SUGGEST_MODULE_NAME" >> deploy.conf
	echo "MODULE_CODE_PATH=$SUGGEST_MODULE_CODE_PATH" >> deploy.conf
	echo "MODULE_DESIGN_PATH=$SUGGEST_MODULE_DESIGN_PATH" >> deploy.conf

	exit;
fi
source deploy.conf

if [[ ( -z "$DEPLOY_TARGET" ) || ( ! -d "$DEPLOY_TARGET" ) ]]; then
	echo "Please give me a valid DEPLOY_TARGET"
	exit
fi

if [[ -z "$MAGENTO_DESIGN_PACKAGE" ]]; then
	MAGENTO_DESIGN_PACKAGE="enterprise/default"
fi

if [[ -z "$MAGENTO_SKIN_PACKAGE" ]]; then
	MAGENTO_SKIN_PACKAGE="enterprise/default"
fi

if [[ -z "$MODULE_NAME" ]]; then
	echo "Please give me a valid MODULE_FRONTEND_NAME"
	exit
fi

if [[ -z "$MODULE_CODE_PATH" ]]; then
	echo "Please give me a valid MODULE_CODE_PATH"
	exit
fi

if [[ -z "$MODULE_DESIGN_PATH" ]]; then
	MODULE_DESIGN_PATH=$MODULE_NAME
	exit
fi


echo "DEPLOY_TARGET           : $DEPLOY_TARGET"
echo "MAGENTO_DESIGN_PACKAGE : $MAGENTO_DESIGN_PACKAGE"
echo "MAGENTO_SKIN_PACKAGE   : $MAGENTO_SKIN_PACKAGE"
echo "MODULE_NAME            : $MODULE_NAME"
echo "MODULE_CODE_PATH       : $MODULE_CODE_PATH"
echo "MODULE_DESIGN_PATH     : $MODULE_DESIGN_PATH"

mkdir -p $DEPLOY_TARGET/app/etc/modules
cp -v $MODULE_NAME.xml $DEPLOY_TARGET/app/etc/modules/$MODULE_NAME.xml

mkdir -p $DEPLOY_TARGET/app/code/local/$MODULE_CODE_PATH
cp -vr code/* $DEPLOY_TARGET/app/code/local/$MODULE_CODE_PATH/

mkdir -p $DEPLOY_TARGET/app/design/frontend/$MAGENTO_DESIGN_PACKAGE/template/$MODULE_DESIGN_PATH
cp -vr template/frontend/* $DEPLOY_TARGET/app/design/frontend/$MAGENTO_DESIGN_PACKAGE/template/$MODULE_DESIGN_PATH/

mkdir -p $DEPLOY_TARGET/app/design/frontend/$MAGENTO_DESIGN_PACKAGE/layout
cp -vr layout/frontend/* $DEPLOY_TARGET/app/design/frontend/$MAGENTO_DESIGN_PACKAGE/layout/

mkdir -p $DEPLOY_TARGET/js/$MODULE_DESIGN_PATH
cp -vr js/* $DEPLOY_TARGET/js/$MODULE_DESIGN_PATH/

mkdir -p $DEPLOY_TARGET/skin/frontend/$MAGENTO_SKIN_PACKAGE/images/$MODULE_DESIGN_PATH
cp -vr images/frontend/* $DEPLOY_TARGET/skin/frontend/$MAGENTO_SKIN_PACKAGE/images/$MODULE_DESIGN_PATH/

