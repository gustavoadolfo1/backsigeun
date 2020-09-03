-- ================================================
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [bib].[Sp_INS_clasificacion_material_detalle]
	  @_iClasiMaterialId INTEGER,
      @_cDescriClasiMaterialDet VARCHAR(30), 
      @_cAbreviadoClasiMatDet VARCHAR(3),
	  @_bHabilitado BIT,		
	/*Campos de auditoria*/
      @_cUsuarioSis VARCHAR(50),
      @_cEquipoSis VARCHAR(50),
      @_cIpSis VARCHAR(15),
      @_cMacNicSis VARCHAR(35)
AS
BEGIN
	SET NOCOUNT ON;
	DECLARE @cUsuarioSis VARCHAR(50)
    SELECT @cUsuarioSis=c.cCredUsuario
    FROM seg.credenciales AS c
    WHERE c.iCredId=@_cUsuarioSis
    IF @@ERROR<>0 GOTO ErrorCapturado

    DECLARE @cMensaje VARCHAR(MAX)

    IF @_iClasiMaterialId IS NULL
		BEGIN
        SET @cMensaje='Falta especificar iClasiMaterialId!, Verifique por favor...'
        RAISERROR (@cMensaje,18,1,1)
        GOTO ErrorCapturado
    END
		
	IF @_cDescriClasiMaterialDet IS NULL
		BEGIN
        SET @cMensaje='Falta especificar cDescriClasiMaterialDet!, Verifique por favor...'
        RAISERROR (@cMensaje,18,1,1)
        GOTO ErrorCapturado
    END

	IF @_cAbreviadoClasiMatDet IS NULL
		BEGIN
        SET @cMensaje='Falta especificar cAbreviadoClasiMatDet!, Verifique por favor...'
        RAISERROR (@cMensaje,18,1,1)
        GOTO ErrorCapturado
    END
		
	
    INSERT INTO bib.clasificacion_material_detalle
        (
        iClasiMaterialId,
		cDescriClasiMaterialDet, 
		cAbreviadoClasiMatDet,
		bHabilitado,		
        cUsuarioSis,
        dFechaSis,
        cEquipoSis,
        cIpSis,
        cOpenUsr,
        cMacNicSis
        )
    VALUES
        (
            @_iClasiMaterialId,
			@_cDescriClasiMaterialDet, 
			@_cAbreviadoClasiMatDet,
			@_bHabilitado,					
            @_cUsuarioSis,
            GETDATE(),
            @_cEquipoSis,
            @_cIpSis,
            'N',
            @_cMacNicSis
		)

    COMMIT TRANSACTION
    SELECT 1 AS iResult
    RETURN 1
ErrorCapturado:
    ROLLBACK TRANSACTION
    SELECT 0 AS iResult
    RETURN 0

END
GO
