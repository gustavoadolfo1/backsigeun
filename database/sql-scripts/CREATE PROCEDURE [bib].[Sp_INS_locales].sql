-- ================================================
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_INS_locales]
	 
	  @_iFilId INT,
      @_iCodigoLocal INT,
      @_cDescriLocal VARCHAR(100),
      @_bHabilitado BIT,
	  /*Campos de auditoria*/
      @_cUsuarioSis VARCHAR(50),
      @_cEquipoSis VARCHAR(50),
      @_cIpSis VARCHAR(15),
      @_cMacNicSis VARCHAR(35)
AS
BEGIN
	SET NOCOUNT ON;
    DECLARE @cUsuarioSis VARCHAR(50),  @cMensaje VARCHAR(MAX), @iLocalId INTEGER
	SET @iLocalId = 0
    SELECT @cUsuarioSis=c.cCredUsuario
    FROM seg.credenciales AS c
    WHERE c.iCredId=@_cUsuarioSis
    IF @@ERROR<>0 GOTO ErrorCapturado

    

    IF @_iFilId IS NULL
		BEGIN
        SET @cMensaje='Falta especificar id filial!, Verifique por favor...'
        RAISERROR (@cMensaje,18,1,1)
        GOTO ErrorCapturado
    END
	
    IF @_iCodigoLocal IS NULL
		BEGIN
        SET @cMensaje='Falta especificar el  codigo local!, Verifique por favor...'
        RAISERROR (@cMensaje,18,1,1)
        GOTO ErrorCapturado
    END
	
    IF @_cDescriLocal IS NULL
		BEGIN
        SET @cMensaje='Falta especificar la descripcion del local!, Verifique por favor...'
        RAISERROR (@cMensaje,18,1,1)
        GOTO ErrorCapturado
    END

    INSERT INTO bib.locales
        (
		iFilId,
        iCodigoLocal,
		cDescriLocal,
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
            @_iFilId,
			@_iCodigoLocal,
			@_cDescriLocal,
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
