-- ================================================
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_INS_clasificacion_material]
	
     @_cDescriMaterial VARCHAR(20),
     @_cAbreviadoClasiMat VARCHAR(3),
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

    IF @_cDescriMaterial IS NULL
		BEGIN
        SET @cMensaje='Falta especificar cDescriMaterial!, Verifique por favor...'
        RAISERROR (@cMensaje,18,1,1)
        GOTO ErrorCapturado
    END
		
	IF @_cAbreviadoClasiMat IS NULL
		BEGIN
        SET @cMensaje='Falta especificar cAbreviadoClasiMat!, Verifique por favor...'
        RAISERROR (@cMensaje,18,1,1)
        GOTO ErrorCapturado
    END
		
	
    INSERT INTO bib.clasificacion_material
        (
        cDescriMaterial,
		cAbreviadoClasiMat,
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
            @_cDescriMaterial,
			@_cAbreviadoClasiMat,
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
